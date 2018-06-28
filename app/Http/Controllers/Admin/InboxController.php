<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Inbox;
use App\Http\Requests\CreateInboxRequest;
use App\Http\Requests\UpdateInboxRequest;
use Illuminate\Http\Request;



class InboxController extends Controller {

	/**
	 * Display a listing of inbox
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $inbox = Inbox::all();

		return view('admin.inbox.index', compact('inbox'));
	}

	/**
	 * Show the form for creating a new inbox
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('admin.inbox.create');
	}

	/**
	 * Store a newly created inbox in storage.
	 *
     * @param CreateInboxRequest|Request $request
	 */
	public function store(CreateInboxRequest $request)
	{
	    
		Inbox::create($request->all());

		return redirect()->route(config('quickadmin.route').'.inbox.index');
	}

	/**
	 * Show the form for editing the specified inbox.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$inbox = Inbox::find($id);
	    
	    
		return view('admin.inbox.edit', compact('inbox'));
	}

	/**
	 * Update the specified inbox in storage.
     * @param UpdateInboxRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateInboxRequest $request)
	{
		$inbox = Inbox::findOrFail($id);

        

		$inbox->update($request->all());

		return redirect()->route(config('quickadmin.route').'.inbox.index');
	}

	/**
	 * Remove the specified inbox from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Inbox::destroy($id);

		return redirect()->route(config('quickadmin.route').'.inbox.index');
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
            Inbox::destroy($toDelete);
        } else {
            Inbox::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route').'.inbox.index');
    }

}
