<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class DownloadController extends Controller
{
    public function avatar(Request $request)
    {
        $type = $request->input('type');
        $file = decrypt($request->input('id'));

        if ($type == 'avatar') {
            $storagePath = public_path('assets/images/avatar/' . $file);
        } else {
            $storagePath = storage_path('app/upload/' . $type . '/' . $file);
        }

        if (!File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file . '"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function research($file)
    {
        $storagePath = storage_path('app/upload/research/' . $file);
        if (!File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file . '"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }

    public function communityService($file)
    {
        $storagePath = storage_path('app/upload/community-service/' . $file);
        if (!File::exists($storagePath)) {
            abort(404);
        } else {
            $mimeType = File::mimeType($storagePath);
            $headers = array(
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file . '"'
            );

            return response(file_get_contents($storagePath), 200, $headers);
        }
    }
}
