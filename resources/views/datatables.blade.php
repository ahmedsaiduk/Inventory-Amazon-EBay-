@extends('layouts.master')
@section('content')
<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-color--white mdl-shadow--2dp">
    <thead>
        <tr>
            <th>No.</th>
            <th class="mdl-data-table__cell--non-numeric">SKU</th>
            <th class="mdl-data-table__cell--non-numeric">Title</th>
            <th>Qty available / sold</th>
            <th class="mdl-data-table__cell--non-numeric">Condition</th>
            <th class="mdl-data-table__cell--non-numeric">Store Category</th>
            <th class="mdl-data-table__cell--non-numeric">Market places</th>
        </tr>
    </thead>
    <tbody>
        <?php $no =1; ?>
        @foreach($spierItems as $spierItem)
            <tr>
                <td><?php echo $no; $no ++; ?></td>
                <td class="mdl-data-table__cell--non-numeric">{{ $spierItem->sku }}</td>
                <td>
                    <a href="{{ url('inventory/items/' . $spierItem->id)}}" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                        {{ strlen($spierItem->title > 25) ?: substr($spierItem->title, 0, 25)}}
                    </a>
                </td>
                <td>{{ $spierItem->quantityAvailable }} / 
                @if($spierItem->quantitySold)
                    {{ $spierItem->quantitySold }}
                @else 0 @endif
                </td>
                <td class="mdl-data-table__cell--non-numeric">{{ $spierItem->condition }}</td>
                <td class="mdl-data-table__cell--non-numeric">
                    {{ $spierItem->store_category->name }}
                </td>
                <td class="mdl-data-table__cell--non-numeric">
                    @foreach($spierItem->ListedOn() as $mp)
                        <img src="{{ asset('images/'. $mp.'-icon.png') }}" width="30px" height="30px">
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection