@extends('layout')

@section('content')
    @if(isset($member))
    <div class="product-details">
        <div class="qr">
            <div >
                <h1 class="product-name">{{ $member->name }}</h1>
                <p class="member-description">{{ $member->description }}</p>
            </div>
            <div class="qr">
                {{QrCode::generate($member->name);}}
            </div>
        </div>

        <div class="member-info">
            <p><strong>Category:</strong> {{ $member->type->name }}</p>
            <p><strong>Price:</strong> ${{ $member->price }}</p>
        </div>
    </div>
    @endif

    <style>
    .product-details {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .product-name {
        font-size: 24px;
        color: #333;
        margin-bottom: 10px;
    }

    .product-description {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }

    .product-info {
        border-top: 1px solid #ccc;
        padding-top: 20px;
    }

    .product-info p {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
    }

    .product-info strong {
        font-weight: bold;
        margin-right: 5px;
    }
    .qr {
        display: flex;
        justify-content: space-between;
    }

</style>

@endsection
