@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Game Registration') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- 登録フォーム -->
                    <form method="POST">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="form-group col-md-8">
                                <label for="game_name">{{ __('Game Name') }}</label>
                                <input id="game_name" type="text" class="form-control @error('game_name') is-invalid @enderror" name="game_name" value="{{ old('game_name') }}" required autofocus>

                                @error('game_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="platform">{{ __('Genre') }}</label>
                                <select id="genre" class="form-control @error('genre') is-invalid @enderror" name="genre" required>
                                    @if(isset($response["genres"]))
                                        @foreach($response["genres"]["items"]["list"] as $genre)
                                            <option value="{{ $genre['genre_key'] }}">{{ $genre["name"] }}</option>
                                        @endforeach         
                                    @endif
                                </select>

                                @error('platform')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-8 mt-3"></div> 
                            <div class="form-group col-md-12">
                                <label for="game_name">{{ __('Setting') }}</label>
                                <table id="perfume" class="table">
                                    <tr>
                                        <td class="text-center">{{  __('Score') }}</td>
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" name="score" value="true"> 
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{  __('Combo') }}</td>
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" name="combo" value="true"> 
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{  __('Rank') }}</td>
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" name="rank" value="true"> 
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{  __('Time') }}</td>
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" name="time" value="true"> 
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">{{  __('Image') }}</td>
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" name="Image" value="true"> 
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group col-md-8 mt-3"></div>  
                            <div class="form-group col-md-4 mt-3">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('Register') }}
                                </button>
                            </div>                          
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">{{ __('Game List') }}</div>
                <div class="m-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Game Key') }}</th>
                                <th scope="col">{{ __('Api Key　　　　　　　　') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Mark</td>
                                <td>
                                    <span id="api-key-1" style="display: none;">YourApiKeyValueHere</span>
                                </td>
                                <td>
                                    <div class="float-end">
                                        <button class="btn btn-sm btn-secondary" onclick="toggleApiKey(1)">Show Key</button>                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Jacob</td>
                                <td>
                                    <span id="api-key-2" style="display: none;">YourApiKeyValueHere</span>
                                </td>
                                <td>
                                    <div class="float-end">
                                        <button class="btn btn-sm btn-secondary" onclick="toggleApiKey(2)">Show Key</button>                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry the Bird</td>
                                <td>Larry the Bird</td>
                                <td>
                                    <span id="api-key-3" style="display: none;">YourApiKeyValueHere</span>
                                </td>
                                <td>
                                    <div class="float-end">
                                        <button class="btn btn-sm btn-secondary" onclick="toggleApiKey(3)">Show Key</button>                                        
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function toggleApiKey(row) {
        const apiKeySpan = document.getElementById(`api-key-${row}`);
        if (apiKeySpan.style.display === "none") {
            apiKeySpan.style.display = "inline";
        } else {
            apiKeySpan.style.display = "none";
        }
    }
</script>