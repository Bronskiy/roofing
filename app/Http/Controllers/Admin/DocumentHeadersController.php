<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\DocumentHeaders;
use App\Http\Requests\CreateDocumentHeadersRequest;
use App\Http\Requests\UpdateDocumentHeadersRequest;
use Illuminate\Http\Request;



class DocumentHeadersController extends Controller {

	/**
	 * Display a listing of documentheaders
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $documentheaders = DocumentHeaders::all();

		return view('admin.documentheaders.index', compact('documentheaders'));
	}

	/**
	 * Show the form for creating a new documentheaders
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('admin.documentheaders.create');
	}

	/**
	 * Store a newly created documentheaders in storage.
	 *
     * @param CreateDocumentHeadersRequest|Request $request
	 */
	public function store(CreateDocumentHeadersRequest $request)
	{
	    
		DocumentHeaders::create($request->all());

		return redirect()->route(config('quickadmin.route').'.documentheaders.index');
	}

	/**
	 * Show the form for editing the specified documentheaders.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$documentheaders = DocumentHeaders::find($id);
	    
	    
		return view('admin.documentheaders.edit', compact('documentheaders'));
	}

	/**
	 * Update the specified documentheaders in storage.
     * @param UpdateDocumentHeadersRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateDocumentHeadersRequest $request)
	{
		$documentheaders = DocumentHeaders::findOrFail($id);

        

		$documentheaders->update($request->all());

		return redirect()->route(config('quickadmin.route').'.documentheaders.index');
	}

	/**
	 * Remove the specified documentheaders from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		DocumentHeaders::destroy($id);

		return redirect()->route(config('quickadmin.route').'.documentheaders.index');
	}

    /**
     * Mass delete function from index page
     * @param Request $request
     *
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            DocumentHeaders::destroy($toDelete);
        } else {
            DocumentHeaders::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route').'.documentheaders.index');
    }

}
