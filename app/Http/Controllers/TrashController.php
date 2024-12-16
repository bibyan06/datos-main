<?php

namespace App\Http\Controllers;

use App\Models\ForwardedDocument;
use App\Models\RequestDocument;
use App\Models\SendDocument;
use App\Models\Document;
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
    public function batch($id,$status,$type){
        if($type=='Forwarded'){
            $forward = ForwardedDocument::where('forwarded_document_id',$id)->first();
            $forward->status = $status;
            if($forward->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Document deleted successfully.',
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Failed to delete a Document.',
                ]);
            }
        }else{
            $document = Document::where('document_id',$id)->first();
            $document->status = $status;
            if($document->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Document deleted successfully.',
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Failed to delete a Document.',
                ]);
            }
        }
    }

    public function deleteNotifForeverdeclined($currentUser){
        $data = Document::where('document_id', $currentUser)->first();
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.',
        ]);
    }
    public function deleteNotifForeverrequested($id){
        $data = RequestDocument::where('request_id', $id)->first();
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.',
        ]);
    }

}
