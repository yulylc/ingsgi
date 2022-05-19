<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Technology;
use Prophecy\Call\Call;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = Candidate::paginate(30);
        return view('candidatos.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tecnologias = Technology::all();
        return view('candidatos.crear', compact('tecnologias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'cv' => 'required|mimes:pdf,docx,doc|max:4096',
        ]);

        $candidato = Candidate::create($request->all());
        $candidato->technologies()->sync($request->tecnologias);

       /*  if ($request->hasFile('cv')) {
        $filename = $request->cv->getClientOriginalName();  
        $candidato['cv'] = $request->file('cv')->storeAs('cv', $filename, 'public');
        $request->file('cv')->storeAs('cv', $filename);
        } */

        /*
        Another way
        if ($request->hasFile('cv')) {
        $candidato['cv'] = $request->file('cv')->store('cv1');
        }
        */

        
       // Another way
        if ($request->hasFile('cv')) {
        $candidato['cv'] = $request->file('cv')->getClientOriginalName();
        $request->file('cv')->storeAs('cv', $candidato['cv']);
        }
       
        //if ($request->hasFile())
        //return redirect()->route('candidatos.index');
        return redirect()->route('candidatos.edit', $candidato)->with('info', 'Solicitud creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Candidate $candidates)
    // {
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidato)
    {
        $tecnologias = Technology::all();
        //$candidatoskills = $candidate->technologies()->pluck('name');
        return view('candidatos.editar', compact('candidato', 'tecnologias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidato)
    {
        $request->validate([
            'name' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'cv' => 'required|mimes:pdf,docx,doc|max:4096',
        ]);

        $candidato->update($request->all());
        $candidato->technologies()->sync($request->tecnologias);

        if ($request->hasFile('cv')) {
            $filename = $request->cv->getClientOriginalName();
            $request->file('cv')->storeAs('cv', $filename, 'public');
        }
        return redirect()->route('candidatos.edit', $candidato)
            ->with('info', 'La solicitud se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidato)
    {
        $candidato->delete();

        return redirect()->route('candidatos.index')->with('info', 'La solicitud se ha eliminado satisfactoriamente');
    }
}
