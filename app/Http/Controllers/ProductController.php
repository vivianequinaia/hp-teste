<?php

namespace App\Http\Controllers;

use App\Adapters\LogMonologAdapter;
use App\Http\Requests\Request;
use App\Repositories\ProductRepository;
use HP\Modules\Products\Create\Requests\Request as CreateRequest;
use HP\Modules\Products\Create\UseCase as CreateUseCase;
use HP\Modules\Products\Delete\Requests\Request as DeleteRequest;
use HP\Modules\Products\Delete\UseCase as DeleteUseCase;
use HP\Modules\Products\Listing\UseCase as ListingUseCase;
use HP\Modules\Products\Show\Requests\Request as ShowRequest;
use HP\Modules\Products\Show\UseCase as ShowUseCase;

class ProductController extends Controller
{
    public function index()
    {
        $useCase = new ListingUseCase(
            new ProductRepository(),
            new LogMonologAdapter()
        );

        $useCase->execute();

        return response()->json(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }

    public function show(int $id)
    {
        $request = new ShowRequest($id);

        $useCase = new ShowUseCase(
            new ProductRepository(),
            new LogMonologAdapter()
        );

        $useCase->execute($request);

        return response()->json(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }

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
