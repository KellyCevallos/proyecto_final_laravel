<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\PagoType;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    //get de pago tupe
    public function getPagoType()
    {
        $pagoType = PagoType::all();
        return response()->json((object)['success'=> true ,'data' => $pagoType], 200);
    }
    public function FinalizarPago(Request $request)
    {
        $request->validate([
            'pagoType_pagoTypeId' => 'required',
            'pagoMonto' => 'required',
        ]);
    
        if ($request->pagoType_pagoTypeId != 2) {
            $request->validate([
                'pagoComprobante' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validación del archivo
            ]);
    
            // Guardar el archivo en storage/app/public/comprobantes
            $filePath = $request->file('pagoComprobante')->store('public/comprobantes');
            $fileUrl = Storage::url($filePath);  // Obtiene la URL pública
        } else {
            $fileUrl = null;
        }
    
        $pago = new Pago();
        $pago->pagoType_pagoTypeId = $request->pagoType_pagoTypeId;
        $pago->pagoMonto = $request->pagoMonto;
        $pago->pagoComprobante = $fileUrl;
        $pago->save();
    
        return response()->json((object)['success' => true, 'pago' => $pago], 200);
    }

}
