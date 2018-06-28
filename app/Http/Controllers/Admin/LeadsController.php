<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Leads;
use App\Http\Requests\CreateLeadsRequest;
use App\Http\Requests\UpdateLeadsRequest;
use Illuminate\Http\Request;

use App\Inbox;


class LeadsController extends Controller {

	/**
	 * Display a listing of leads
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $leads = Leads::with("inbox")->get();

		return view('admin.leads.index', compact('leads'));
	}

	/**
	 * Show the form for creating a new leads
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $inbox = Inbox::pluck("inbox_date", "id")->prepend('Please select', 0);

	    
	    return view('admin.leads.create', compact("inbox"));
	}

	/**
	 * Store a newly created leads in storage.
	 *
     * @param CreateLeadsRequest|Request $request
	 */
	public function store(CreateLeadsRequest $request)
	{
	    
		Leads::create($request->all());

		return redirect()->route(config('quickadmin.route').'.leads.index');
	}

	/**
	 * Show the form for editing the specified leads.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$leads = Leads::find($id);
	    $inbox = Inbox::pluck("inbox_date", "id")->prepend('Please select', 0);

	    
		return view('admin.leads.edit', compact('leads', "inbox"));
	}

	/**
	 * Update the specified leads in storage.
     * @param UpdateLeadsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateLeadsRequest $request)
	{
		$leads = Leads::findOrFail($id);

        

		$leads->update($request->all());

		return redirect()->route(config('quickadmin.route').'.leads.index');
	}

	/**
	 * Remove the specified leads from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Leads::destroy($id);

		return redirect()->route(config('quickadmin.route').'.leads.index');
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
            Leads::destroy($toDelete);
        } else {
            Leads::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route').'.leads.index');
    }

}
