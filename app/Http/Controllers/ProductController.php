<?php

namespace App\Http\Controllers;

use App\Adapters\LogMonologAdapter;
use App\Http\Requests\Request;
use App\Repositories\ProductRepository;
use HP\Modules\Products\Create\Requests\Request as CreateRequest;
use HP\Modules\Products\Create\UseCase;

class ProductController extends Controller
{

    public function store(Request $httpRequest)
    {
        $request = new CreateRequest(
            $httpRequest->get('name'),
            $httpRequest->get('amount'),
            $httpRequest->get('qty_stock')
        );

        $useCase = new UseCase(
            new ProductRepository(),
            new LogMonologAdapter()
        );

        $useCase->execute($request);

        return response()->json(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }
}
