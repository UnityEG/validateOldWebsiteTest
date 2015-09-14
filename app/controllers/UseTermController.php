<?php

class UseTermController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function UpdateListOrder() {
        //
//        print_r(Input::only('NewListOrder'));
        //
        $NewListOrder = Input::only('NewListOrder');
        $order = 1;
        foreach ($NewListOrder as $o) {
            foreach ($o as $order => $id) {
                $input = array('list_order' => $order);
                $UseTerm = UseTerm::find($id);
                $UseTerm->update($input);
//                echo 'id: ' . $id . ', list_order: ' . $order;
//                $order++;
            }
        }
    }

    public function index() {
        // return 'You are in UseTerms.index';
        $UseTerms = UseTerm::orderBy('list_order', 'asc')->get();
        return View::make('UseTerms.index', compact('UseTerms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
        return View::make('UseTerms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
        $input = Input::all(); // except('xxx'); // only('xxx');

        $validation = Validator::make($input, UseTerm::$rules);

        if ($validation->passes()) {
            UseTerm::create($input);
            return Redirect::route('UseTerms.index');
        }
        return Redirect::route('UseTerms.create')
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        $UseTerm = UseTerm::find($id);
        if (is_null($UseTerm)) {
            return Redirect::route('UseTerms.index');
        }
        return View::make('UseTerms.show', compact('UseTerm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
        $UseTerm = UseTerm::find($id);
        if (is_null($UseTerm)) {
            return Redirect::route('UseTerms.index');
        }
        return View::make('UseTerms.edit', compact('UseTerm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
        $input = Input::all();

        $validation = Validator::make($input, UseTerm::$rules);

        if ($validation->passes()) {
            $UseTerm = UseTerm::find($id);
            $UseTerm->update($input);
            return Redirect::route('UseTerms.show', $id);
        }
        return Redirect::route('UseTerms.edit', $id)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
        UseTerm::find($id)->delete();
        return Redirect::route('UseTerms.index');
    }

}
