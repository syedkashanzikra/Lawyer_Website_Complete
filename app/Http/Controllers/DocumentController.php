<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\DocSubType;
use App\Models\DocType;
use App\Models\Document;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage document')) {
            $docs = Document::where('created_by', Auth::user()->creatorId())->with('user','getDocType')->get();
            
            return view('documents.index', compact('docs'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create document')) {
            $types = DocType::where('created_by',Auth::user()->creatorId())->get()->pluck('name', 'id');
            $cases = Cases::where('created_by',Auth::user()->creatorId())->get()->pluck('title','id');
            return view('documents.create', compact('types','cases'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('create document')) {
            $doc = new Document();
            $doc['name'] = $request->name;
            $doc['type'] = $request->type;
            $doc['purpose'] = $request->purpose;
            $doc['description'] = $request->description;
            $doc['document_subtype'] = $request->document_subtype;
            $doc['created_by'] = Auth::user()->creatorId();
            $doc['cases'] =  $request->cases;
            $doc['doc_link'] = $request->doc_link;
            if(!empty($request->file('file'))){

                $image_size = $request->file('file')->getSize();
                $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);

                if($result==1) {
                    $filenameWithExt = $request->file('file')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')->getClientOriginalExtension();
                    $fileNameToStores = 'document_' . time() . '.' . $extension;

                    $settings = Utility::getStorageSetting();
                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/documents/';
                    } else {
                        $dir = 'uploads/documents/';
                    }
                    $path = Utility::upload_file($request, 'file', $fileNameToStores, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    $filesize = number_format($request->file('file')->getSize() / 1000000, 4);
                    $doc['file'] = $fileNameToStores;
                    $doc['doc_size'] = $filesize;
                }
            }
            $doc->save();

            return redirect()->route('documents.index')->with('success', __('Document successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->can('view document')) {
            $doc = Document::find($id);
            $cases = '-';
            if(!empty($doc->cases)){
                $cases = Cases::whereIn('id',explode(',',$doc->cases))->get()->pluck('title')->toArray();
                $cases = implode(',',$cases);

            }
            return view('documents.view', compact('doc','cases'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit document')) {
            $doc = Document::find($id);
            $types = DocType::where('created_by',Auth::user()->creatorId())->orWhere('created_by',0)->get()->pluck('name', 'id');
            $cases = Cases::where('created_by',Auth::user()->creatorId())->get()->pluck('title','id');
            $your_cases= [];
            if (!empty($doc->cases)) {
                $your_cases = Cases::whereIn('id', explode(',', $doc->cases))->get();
            }
            $doc_typ =$doc->type;

            return view('documents.edit', compact('doc', 'types','cases','doc_typ','your_cases'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit document')) {

            $doc = Document::find($id);
            $doc['name'] = $request->name;
            $doc['type'] = $request->type;
            $doc['purpose'] = $request->purpose;
            $doc['description'] = $request->description;
            $doc['created_by'] = Auth::user()->id;
            $doc['document_subtype'] = $request->document_subtype;
            $doc['cases'] =  $request->cases;
            $doc['doc_link'] = $request->doc_link;
            if(!empty($request->file('file'))){
                $dir        = 'uploads/documents/';
                $file_path = $dir.$doc->file;

                $image_size = $request->file('file')->getSize();

                $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                if($result==1) {

                    Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $filenameWithExt = $request->file('file')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')->getClientOriginalExtension();
                    $fileNameToStores = 'document_' . time() . '.' . $extension;

                    $settings = Utility::getStorageSetting();
                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/documents/';
                    } else {
                        $dir = 'uploads/documents/';
                    }
                    $path = Utility::upload_file($request, 'file', $fileNameToStores, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    $filesize = number_format($request->file('file')->getSize() / 1000000, 4);

                    $doc['file'] = $fileNameToStores;
                    $doc['doc_size'] = $filesize;
                }
            }

            $doc->save();

            return redirect()->route('documents.index')->with('success', __('Document successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('delete document')) {
            $doc = Document::find($id);
            if ($doc) {

                $filepath = storage_path('uploads/documents/'.$doc->file);
                Utility::changeStorageLimit(Auth::user()->creatorId(),'uploads/documents/'.$doc->file);

                if (File::exists($filepath)) {
                    File::delete($filepath);
                }

                $doc->delete();
            }
            return redirect()->route('documents.index')->with('success', __('Document successfully deleted.'));


        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }
}
