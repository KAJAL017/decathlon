<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaSetting;
use App\Models\Setting;
use Illuminate\Http\Request;

class MediaSettingsController extends Controller
{
    public function index()
    {
        $types = MediaSetting::orderBy('image_type')->get();
        $globalSettings = Setting::group('media');
        
        // Ensure default global settings exist for view
        $globalSettings = array_merge([
            'max_upload_size' => 500,
            'allowed_extensions' => 'jpg,jpeg,png,webp',
            'auto_delete_old' => true,
            'auto_rename' => true,
            'unique_filenames' => true,
            'strip_metadata' => true,
        ], $globalSettings);

        return view('admin.pages.settings.media', compact('types', 'globalSettings'));
    }

    public function updateGlobal(Request $request)
    {
        // Convert checkbox-style presence into strict booleans before validation
        $request->merge([
            'auto_delete_old' => $request->has('auto_delete_old'),
            'auto_rename' => $request->has('auto_rename'),
            'unique_filenames' => $request->has('unique_filenames'),
            'strip_metadata' => $request->has('strip_metadata'),
        ]);

        $data = $request->validate([
            'max_upload_size' => 'required|integer|min:1',
            'allowed_extensions' => 'required|string',
            'auto_delete_old' => 'boolean',
            'auto_rename' => 'boolean',
            'unique_filenames' => 'boolean',
            'strip_metadata' => 'boolean',
        ]);

        Setting::saveMany($data, 'media');

        return response()->json([
            'success' => true,
            'message' => 'Global media settings updated successfully'
        ]);
    }

    public function updateType(Request $request, $id)
    {
        $type = MediaSetting::findOrFail($id);

        // Convert checkbox-style presence into strict booleans before validation
        $request->merge([
            'keep_aspect_ratio' => $request->has('keep_aspect_ratio'),
            'prevent_upscaling' => $request->has('prevent_upscaling'),
            'auto_optimize' => $request->has('auto_optimize'),
            'generate_thumbnail' => $request->has('generate_thumbnail'),
        ]);

        $data = $request->validate([
            'max_width' => 'required|integer|min:1',
            'max_height' => 'required|integer|min:1',
            'quality' => 'required|integer|min:1|max:100',
            'format' => 'required|in:webp,jpg,png,original',
            'keep_aspect_ratio' => 'boolean',
            'prevent_upscaling' => 'boolean',
            'auto_optimize' => 'boolean',
            'generate_thumbnail' => 'boolean',
            'thumbnail_width' => 'nullable|required_if:generate_thumbnail,1,true|integer|min:1',
            'thumbnail_height' => 'nullable|required_if:generate_thumbnail,1,true|integer|min:1',
        ]);

        $type->update($data);

        return response()->json([
            'success' => true,
            'message' => "Settings for {$type->image_type} updated successfully"
        ]);
    }

    public function resetType($id)
    {
        $type = MediaSetting::findOrFail($id);
        $defaults = MediaSetting::getDefaults($type->image_type);
        
        $type->update($defaults->toArray());

        return response()->json([
            'success' => true,
            'message' => "Settings for {$type->image_type} reset to defaults",
            'data' => $type
        ]);
    }
}
