<?php
// php artisan make:controller NastavnikController --resource
namespace Fakultet\Http\Controllers;
use Fakultet\Nastavnik;
use Fakultet\Stud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\Validator;  //koristiti cemo alias
use Validator;
use Input;
use Redirect;
use View;
class NastavnikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $nastavnici = Nastavnik::all()->reverse();
        return View::make('fakultet.nastavnik.index')
                        ->with('nastavnici', $nastavnici);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('fakultet.nastavnik.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //TODO napravi validaciju
        $n = new Nastavnik;
        $n->imeNastavnik=Input::get('imeNastavnik');
        $n->prezNastavnik=Input::get('prezNastavnik');
        $n->pbrStan=Input::get('pbrStan');
        $n->sifOrgjed=Input::get('sifOrgjed');
        $n->koef=Input::get('koef');
        //$n->sifNastavnik=999;
        $n->save();
        
        return Redirect::to('nastavnik');
 
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
     $n = Nastavnik::find($id);
        return View::make('fakultet.nastavnik.show')
                        ->with('nastavnici', $n);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $n = Nastavnik::find($id);
        // Pokazi formu za editiranje studenata
        return View::make('fakultet.nastavnik.edit')
                        ->with('nastavnik', $n);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
       // validate
       // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'imeNastavnik'=> 'required',
            'prezNastavnik' => 'required',
            'pbrStan' => 'required|numeric',
            'sifOrgjed' => 'required|numeric',
            'koef' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return Redirect::to('nastavnik/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {
            // store
            $nastavnik = Nastavnik::find($id);
                     
            $nastavnik->imeNastavnik= Input::get('imeNastavnik');
            $nastavnik->prezNastavnik = Input::get('prezNastavnik');
            $nastavnik->pbrStan = Input::get('pbrStan');
            $nastavnik->sifOrgjed = Input::get('sifOrgjed');
            $nastavnik->koef = Input::get('koef');    
        
            $nastavnik->save();
            // redirect
            Session::flash('message', 'Uspjesno uređen nastavnik!');
            return Redirect::to('nastavnik');
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $n = Nastavnik::find($id);
        $n->delete();
        // redirect
        Session::flash('message', 'Nastavnik je uspješno obrisan!');
        return Redirect::to('nastavnik');
    }
        /**
     * Ova funkcija vraća listu nastavnika sa najvećim koeficijentom
     * (zasad sa koef > 7.10)
     */
    function top() {
    $d1 = Nastavnik::all()
            ->where('koef', '>', '7.10')
            ->take(5);
    return "<h1>Nastavnici sa koeficijentom većim od 7.10, limit 5</h1><br>" 
.$d1;
//TODO želim ispisati samo ime i prezime nastavnika
//BUG Ispravi i ispisi top 10 nastavnika!    
//. $d1->attributes['imeNastavnik'];
    }
}