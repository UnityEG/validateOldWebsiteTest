@extends('layouts.main')
@section('main')
<div class="forms-back">
    <p><a class="btn btn-success" href="{{route('UseTerms.create')}}"><span class="glyphicon glyphicon-plus"></span> Add New Term Of Use</a>
        <button id="opener" class="btn btn-default">Reorder Terms List</button>
    </p>

    <h2>All terms of use</h2>

    @if (!$UseTerms->count())
    There are no term of use
    @else
    <table class="table table-hover list-table">
        <thead>
            <tr>
                <th>Term of use</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($UseTerms as $UseTerm)
            <tr>
                <td>{{ link_to_route('UseTerms.show', $UseTerm->name, array($UseTerm->id)) }}</td>
                <td>
                    <a href="{{route('UseTerms.edit', $UseTerm->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
                    {{ Form::open(array('method' => 'DELETE', 'route' => array('UseTerms.destroy', $UseTerm->id))) }}
                    {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return ConfirmDelete()'))}}
                    {{Form::close()}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- $UseTerms->links() --}}
</div>
<script>
    $(function () {
        $("#dialog").dialog({
            width: "40%",
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 500
            },
            hide: {
                effect: "blind",
                duration: 500
            },
            close: function (event, ui) {
                location.reload();
            }
        });

        $("#opener").click(function () {
            $("#dialog").dialog("open");
        });

        $("#sortable").sortable({opacity: 0.8, cursor: 'move', update: function () {
                var NewListOrder = $(this).sortable("serialize");
                $.post("UseTerms/UpdateListOrder", NewListOrder, function (theResponse) {

                });
//                alert(order);
            }});
        $("#sortable").disableSelection();
    });
</script>
<div id="dialog" title="Reorder Terms List">
    <ul id="sortable">
        @foreach ($UseTerms as $UseTerm)
        <li id="NewListOrder_{{$UseTerm->id}}">{{ $UseTerm->name }}</li>
        @endforeach
    </ul>
</div>
<script>
    function ConfirmDelete() {
        var r = confirm("This item will be permanently deleted and cannot be recovered. Are you sure?");
        if (r == true) {
            //txt = "You pressed OK!";
        } else {
            //txt = "You pressed Cancel!";
            return false;
        }
    }
</script>
@endif
@stop