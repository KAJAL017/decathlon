<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImportExportJob;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductTag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Writer;

class ProductImportExportService
{
    protected $defaultExportFields = [
        'id',
        'name',
        'slug',
        'sku_prefix',
        'brand_name',
        'category_name',
        'short_description',
        'description',
        'product_type',
        'status',
        'availability_status',
        'available_date',
        'visibility',
        'is_digital',
        'download_url',
        'download_limit',
        'is_featured',
        'is_new',
        'is_best_seller',
        'weight',
        'length',
        'width',
        'height',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'tags',
        'created_at',
        'updated_at'
    ];

    protected $requiredImportFields = [
        'name',
        'product_type'
    ];

    public function exportProducts($storeId = null, $settings = [])
    {
        // Create export job
        $job = ProductImportExportJob::create([
            'store_id' => $storeId,
            'user_id' => auth()->id(),
            'type' => 'export',
            'format' => $settings['format'] ?? 'csv',
            'status' => 'pending',
            'settings' => $settings,
        ]);

        try {
            $job->markAsProcessing();

            // Get products query
            $query = Product::with(['brand', 'category', 'tags']);
            
            if ($storeId) {
                $query->where('store_id', $storeId);
            }

            // Apply filters from settings
            if (!empty($settings['status'])) {
                $query->where('status', $settings['status']);
            }
            
            if (!empty($settings['product_type'])) {
                $query->where('product_type', $settings['product_type']);
            }
            
            if (!empty($settings['brand_ids'])) {
                $query->whereIn('brand_id', $settings['brand_ids']);
            }
            
            if (!empty($settings['category_ids'])) {
                $query->whereIn('category_id', $settings['category_ids']);
            }

            $products = $query->get();
            $job->update(['total_records' => $products->count()]);

            // Generate export file
            $fileName = 'products_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $filePath = 'exports/' . $fileName;

            $csv = Writer::createFromString('');
            
            // Add headers
            $fields = $settings['fields'] ?? $this->defaultExportFields;
            $csv->insertOne($fields);

            // Add data
            $processed = 0;
            foreach ($products as $product) {
                $row = [];
                foreach ($fields as $field) {
                    $row[] = $this->getProductFieldValue($product, $field);
                }
                $csv->insertOne($row);
                $processed++;
                
                // Update progress every 100 records
                if ($processed % 100 == 0) {
                    $job->updateProgress($processed, $processed, 0);
                }
            }

            // Save file
            Storage::put($filePath, $csv->toString());
            
            $job->update([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'download_url' => Storage::url($filePath),
                'processed_records' => $processed,
                'successful_records' => $processed,
            ]);

            $job->markAsCompleted();

            return $job;

        } catch (\Exception $e) {
            $job->markAsFailed(['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function importProducts($filePath, $storeId = null, $settings = [])
    {
        // Create import job
        $job = ProductImportExportJob::create([
            'store_id' => $storeId,
            'user_id' => auth()->id(),
            'type' => 'import',
            'format' => $settings['format'] ?? 'csv',
            'status' => 'pending',
            'file_path' => $filePath,
            'settings' => $settings,
            'field_mapping' => $settings['field_mapping'] ?? [],
        ]);

        try {
            $job->markAsProcessing();

            // Read CSV file
            $csv = Reader::createFromPath(Storage::path($filePath), 'r');
            $csv->setHeaderOffset(0);
            
            $records = iterator_to_array($csv->getRecords());
            $job->update(['total_records' => count($records)]);

            $processed = 0;
            $successful = 0;
            $failed = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($records as $index => $record) {
                try {
                    $this->importSingleProduct($record, $storeId, $settings);
                    $successful++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'row' => $index + 2, // +2 because of header and 0-based index
                        'error' => $e->getMessage(),
                        'data' => $record
                    ];
                }
                
                $processed++;
                
                // Update progress every 50 records
                if ($processed % 50 == 0) {
                    $job->updateProgress($processed, $successful, $failed, $errors);
                }
            }

            DB::commit();

            $job->updateProgress($processed, $successful, $failed, $errors);
            $job->markAsCompleted();

            return $job;

        } catch (\Exception $e) {
            DB::rollBack();
            $job->markAsFailed(['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function importSingleProduct($record, $storeId, $settings)
    {
        $mapping = $settings['field_mapping'] ?? [];
        
        // Map fields
        $data = [];
        foreach ($mapping as $csvField => $productField) {
            if (isset($record[$csvField])) {
                $data[$productField] = $record[$csvField];
            }
        }

        // Validate required fields
        foreach ($this->requiredImportFields as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Required field '{$field}' is missing or empty");
            }
        }

        // Process brand
        if (!empty($data['brand_name'])) {
            $brand = Brand::firstOrCreate(
                ['name' => $data['brand_name'], 'store_id' => $storeId],
                ['slug' => Str::slug($data['brand_name']), 'status' => true]
            );
            $data['brand_id'] = $brand->id;
        }
        unset($data['brand_name']);

        // Process category
        if (!empty($data['category_name'])) {
            $category = Category::firstOrCreate(
                ['name' => $data['category_name'], 'store_id' => $storeId],
                ['slug' => Str::slug($data['category_name']), 'status' => true]
            );
            $data['category_id'] = $category->id;
        }
        unset($data['category_name']);

        // Process tags
        $tags = [];
        if (!empty($data['tags'])) {
            $tagNames = explode(',', $data['tags']);
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if ($tagName) {
                    $tag = ProductTag::firstOrCreate(
                        ['name' => $tagName, 'store_id' => $storeId],
                        ['slug' => Str::slug($tagName)]
                    );
                    $tags[] = $tag->id;
                }
            }
        }
        unset($data['tags']);

        // Set defaults
        $data['store_id'] = $storeId;
        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';
        $data['product_type'] = $data['product_type'] ?? 'simple';
        $data['visibility'] = $data['visibility'] ?? 'visible';

        // Handle update vs create
        $product = null;
        if (!empty($settings['update_existing']) && !empty($data['id'])) {
            $product = Product::where('id', $data['id'])
                             ->where('store_id', $storeId)
                             ->first();
        } elseif (!empty($settings['update_by_sku']) && !empty($data['sku_prefix'])) {
            $product = Product::where('sku_prefix', $data['sku_prefix'])
                             ->where('store_id', $storeId)
                             ->first();
        }

        if ($product) {
            $product->update($data);
        } else {
            unset($data['id']); // Don't set ID for new products
            $product = Product::create($data);
        }

        // Sync tags
        if (!empty($tags)) {
            $product->tags()->sync($tags);
        }

        return $product;
    }

    protected function getProductFieldValue($product, $field)
    {
        return match($field) {
            'brand_name' => $product->brand?->name ?? '',
            'category_name' => $product->category?->name ?? '',
            'tags' => $product->tags->pluck('name')->implode(', '),
            'available_date' => $product->available_date?->format('Y-m-d') ?? '',
            'created_at' => $product->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            default => $product->{$field} ?? ''
        };
    }

    public function getExportTemplate()
    {
        $csv = Writer::createFromString('');
        
        // Add headers with sample data
        $csv->insertOne($this->defaultExportFields);
        $csv->insertOne([
            '1',
            'Sample Product Name',
            'sample-product-name',
            'SAMPLE',
            'Sample Brand',
            'Sample Category',
            'Short description of the product',
            'Detailed description of the product',
            'simple',
            'active',
            'in_stock',
            '',
            'visible',
            '0',
            '',
            '0',
            '0',
            '0',
            '0',
            '1.50',
            '10.00',
            '5.00',
            '3.00',
            'Sample Product - SEO Title',
            'Sample product SEO description',
            'sample, product, keywords',
            'tag1, tag2, tag3',
            now()->format('Y-m-d H:i:s'),
            now()->format('Y-m-d H:i:s')
        ]);

        return $csv->toString();
    }
}