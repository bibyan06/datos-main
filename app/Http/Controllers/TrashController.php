<?php

namespace App\Http\Controllers;

use App\Models\ForwardedDocument;
use App\Models\SendDocument;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function deleteNotifForever($id){
        $data = ForwardedDocument::where('forwarded_document_id',$id)->first();
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.',
        ]);
    }
    public function deleteNotifForeversent($id){
        $data = SendDocument::where('send_id',$id)->first();
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.',
        ]);
    }


}
