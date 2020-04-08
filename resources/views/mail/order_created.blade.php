<p>Уважаемый {{$name}}</p>
<p>@lang('mail.order_created.you_order') на сумму {{$fullSum}} создан</p>

<table>
    <tbody>
    @foreach($order->product as $product)
        <tr>
            <td>
                <a href="{{route('product', [$product->category->code,$product->code])}}">
                    <img height="56px" src="{{Storage::url($product->image)}}">
                    {{$product->name}}
                </a>
            </td>
            <td><span class="badge">{{$product->pivot->count}}</span>
                <div class="btn-group form-inline">
                    {{$product->description}}
                </div>
            </td>
            <td> {{$product->price}} руб.</td>
            {{--<td> {{$product->getPriceForCount($product->pivot->count)}} руб.</td>--}}
            <td> {{$product->getPriceForCount()}} руб.</td>

        </tr>
        @endforeach
    </tbody>
</table>
