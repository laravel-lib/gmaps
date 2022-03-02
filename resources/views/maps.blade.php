@foreach ($items as $id => $item)
    {!! $item->render($id, $view) !!}

    @if (($id+1) == count($totalItems))
        <script type="text/javascript">
            window.mapLoader.load().then(() => {
                window.initMaps.forEach(m => m.method());
            });
        </script>
    @endif
@endforeach
