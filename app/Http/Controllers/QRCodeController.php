<?php

namespace App\Http\Controllers;

use chillerlan\QRCode\QRCode;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class QRCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qrcode = auth('web')->user()->qrcode()->first();
        
        while (!$qrcode) {
            try {
                $qrcode = auth('web')->user()->qrcode()->create([
                    'code' => Random::generate(10),
                ]);
            } catch (\Exception $e) {
                $qrcode = null;
            }
        }

        $generator = new QRCode();
        // $matrix = $generator->getQRMatrix();
        // $matrix->
        $data = $generator->render($qrcode->code);
        
        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
