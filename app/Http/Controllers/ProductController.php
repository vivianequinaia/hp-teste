<?php

namespace App\Http\Controllers;

use App\Adapters\LogMonologAdapter;
use App\Http\Requests\Request;
use App\Repositories\ProductRepository;
use HP\Modules\Products\Create\Requests\Request as CreateRequest;
use HP\Modules\Products\Create\UseCase as CreateUseCase;
use HP\Modules\Products\Delete\Requests\Request as DeleteRequest;
use HP\Modules\Products\Delete\UseCase as DeleteUseCase;

class ProductController extends Controller
{
    public function store(Request $httpRequest)
    {
        $request = new CreateRequest(
            $httpRequest->get('name'),
            $httpRequest->get('amount'),
            $httpRequest->get('qty_stock')
        );

        $useCase = new CreateUseCase(
            new ProductRepository(),
            new LogMonologAdapter()
        );

        $useCase->execute($request);

        return response()->json(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }

    public function destroy(int $id)
    {
        $request = new DeleteRequest($id);

        $useCase = new DeleteUseCase(
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
