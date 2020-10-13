<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Http\Resources\Admin\TaxResource;
use App\Tax;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tax_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TaxResource(Tax::with(['countries'])->get());
    }

    public function store(StoreTaxRequest $request)
    {
        $tax = Tax::create($request->all());
        $tax->countries()->sync($request->input('countries', []));

        return (new TaxResource($tax))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tax $tax)
    {
        abort_if(Gate::denies('tax_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TaxResource($tax->load(['countries']));
    }

    public function update(UpdateTaxRequest $request, Tax $tax)
    {
        $tax->update($request->all());
        $tax->countries()->sync($request->input('countries', []));

        return (new TaxResource($tax))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tax $tax)
    {
        abort_if(Gate::denies('tax_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tax->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
