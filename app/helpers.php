<?php

if (!function_exists('customerView')) {
    function customerView(string $desktopView, string $mobileView, array $data = []): \Illuminate\View\View
    {
        $isMobile = request()->attributes->get('is_mobile', false);

        return view($isMobile ? $mobileView : $desktopView, $data);
    }
}
