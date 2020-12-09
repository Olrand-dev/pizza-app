<div>
    <ul class="nav">

        @foreach ($elements as $e)

            @can("show-mm-element-{$e['slug']}")
            <li @if (session('page_slug') === $e['slug']) class="active" @endif>

                <a href="{{ $e['url'] }}">
                    <i class="{{ $e['icon'] }}"></i>
                    <p>{{ $e['name'] }}</p>
                </a>
            </li>
            @endcan

        @endforeach

    </ul>
</div>
