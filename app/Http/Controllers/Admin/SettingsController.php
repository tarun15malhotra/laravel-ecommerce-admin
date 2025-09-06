<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllByGroup();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function general()
    {
        $settings = Setting::where('group', 'general')->get()->pluck('value', 'key');
        
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_email' => 'required|email',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
            'store_city' => 'nullable|string|max:100',
            'store_country' => 'nullable|string|max:100',
            'store_postal_code' => 'nullable|string|max:20',
            'store_logo' => 'nullable|image|max:2048',
            'store_favicon' => 'nullable|image|max:512',
            'currency' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:5',
            'timezone' => 'required|timezone',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            $logo = $request->file('store_logo');
            $path = $logo->store('settings', 'public');
            
            // Resize logo
            $img = Image::read(storage_path('app/public/' . $path));
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save();
            
            $validated['store_logo'] = $path;
        }

        // Handle favicon upload
        if ($request->hasFile('store_favicon')) {
            $favicon = $request->file('store_favicon');
            $path = $favicon->store('settings', 'public');
            
            // Resize favicon
            $img = Image::read(storage_path('app/public/' . $path));
            $img->resize(32, 32);
            $img->save();
            
            $validated['store_favicon'] = $path;
        }

        // Save settings
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'text');
        }

        return redirect()->route('admin.settings.general')
            ->with('success', 'General settings updated successfully.');
    }

    public function payment()
    {
        $settings = Setting::where('group', 'payment')->get()->pluck('value', 'key');
        
        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_cod_enabled' => 'boolean',
            'payment_cod_instructions' => 'nullable|string',
            'payment_paypal_enabled' => 'boolean',
            'payment_paypal_mode' => 'nullable|in:sandbox,live',
            'payment_paypal_client_id' => 'nullable|string',
            'payment_paypal_client_secret' => 'nullable|string',
            'payment_stripe_enabled' => 'boolean',
            'payment_stripe_mode' => 'nullable|in:test,live',
            'payment_stripe_publishable_key' => 'nullable|string',
            'payment_stripe_secret_key' => 'nullable|string',
            'payment_stripe_webhook_secret' => 'nullable|string',
            'payment_bank_transfer_enabled' => 'boolean',
            'payment_bank_transfer_instructions' => 'nullable|string',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            $type = str_contains($key, '_enabled') ? 'boolean' : 'text';
            Setting::set($key, $value, $type);
        }

        return redirect()->route('admin.settings.payment')
            ->with('success', 'Payment settings updated successfully.');
    }

    public function shipping()
    {
        $settings = Setting::where('group', 'shipping')->get()->pluck('value', 'key');
        
        return view('admin.settings.shipping', compact('settings'));
    }

    public function updateShipping(Request $request)
    {
        $validated = $request->validate([
            'shipping_flat_rate_enabled' => 'boolean',
            'shipping_flat_rate_cost' => 'nullable|numeric|min:0',
            'shipping_free_enabled' => 'boolean',
            'shipping_free_minimum' => 'nullable|numeric|min:0',
            'shipping_local_pickup_enabled' => 'boolean',
            'shipping_local_pickup_instructions' => 'nullable|string',
            'shipping_express_enabled' => 'boolean',
            'shipping_express_cost' => 'nullable|numeric|min:0',
            'shipping_express_days' => 'nullable|integer|min:1',
            'shipping_standard_enabled' => 'boolean',
            'shipping_standard_cost' => 'nullable|numeric|min:0',
            'shipping_standard_days' => 'nullable|integer|min:1',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            $type = str_contains($key, '_enabled') ? 'boolean' : 'text';
            Setting::set($key, $value, $type);
        }

        return redirect()->route('admin.settings.shipping')
            ->with('success', 'Shipping settings updated successfully.');
    }

    public function email()
    {
        $settings = Setting::where('group', 'email')->get()->pluck('value', 'key');
        
        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|in:null,tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'email_order_confirmation' => 'boolean',
            'email_order_shipped' => 'boolean',
            'email_order_delivered' => 'boolean',
            'email_low_stock_alert' => 'boolean',
            'email_admin_new_order' => 'boolean',
            'email_admin_email' => 'nullable|email',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            $type = str_contains($key, 'email_') && !str_contains($key, '_email') ? 'boolean' : 'text';
            Setting::set($key, $value, $type);
        }

        // Update .env file for mail settings
        $this->updateEnvFile([
            'MAIL_MAILER' => $validated['mail_driver'],
            'MAIL_HOST' => $validated['mail_host'],
            'MAIL_PORT' => $validated['mail_port'],
            'MAIL_USERNAME' => $validated['mail_username'],
            'MAIL_PASSWORD' => $validated['mail_password'],
            'MAIL_ENCRYPTION' => $validated['mail_encryption'],
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME' => $validated['mail_from_name'],
        ]);

        return redirect()->route('admin.settings.email')
            ->with('success', 'Email settings updated successfully.');
    }

    public function appearance()
    {
        $settings = Setting::where('group', 'appearance')->get()->pluck('value', 'key');
        
        return view('admin.settings.appearance', compact('settings'));
    }

    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'dark_mode_enabled' => 'boolean',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'items_per_page' => 'required|integer|min:10|max:100',
            'show_breadcrumbs' => 'boolean',
            'sidebar_collapsed' => 'boolean',
            'enable_animations' => 'boolean',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            $type = in_array($key, ['dark_mode_enabled', 'show_breadcrumbs', 'sidebar_collapsed', 'enable_animations']) ? 'boolean' : 'text';
            Setting::set($key, $value, $type);
        }

        return redirect()->route('admin.settings.appearance')
            ->with('success', 'Appearance settings updated successfully.');
    }

    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $str = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            if (preg_match("/^{$key}=/m", $str)) {
                $str = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $str);
            } else {
                $str .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envFile, $str);
    }
}
