@if(session('feedbacks') && count(session('feedbacks')))
    <script>
        $(function () {
            @foreach(session('feedbacks') as $feedback)
            $.toast({
                heading: '{{ $feedback['title'] }}',
                text: '{{ $feedback['body'] }}',
                showHideTransition: 'slide',
                icon: '{{ $feedback['type'] }}'
            });
            @endforeach
        });
    </script>
@endif
