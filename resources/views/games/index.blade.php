@section('content')
<div class="container py-6">
  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <h1 class="mb-4">Jogos</h1>
  <div class="row g-3">
    @forelse ($games as $game)
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <img src="{{ $game['image_url'] }}" class="card-img-top" alt="{{ $game['name'] }}">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">{{ $game['name'] }}</h6>
            <form method="POST" action="{{ route('casino.launch') }}" class="mt-auto" target="_blank" rel="noopener">
              @csrf
              <input type="hidden" name="game_code" value="{{ $game['game_code'] }}">
              <input type="hidden" name="game_original" value="{{ $game['original'] ? '1':'0' }}">
              <button class="btn btn-primary w-100" type="submit">Jogar</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <p>Nenhum jogo disponível.</p>
    @endforelse
  </div>
</div>
@endsection
