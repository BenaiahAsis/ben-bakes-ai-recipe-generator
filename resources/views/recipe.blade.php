<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ben Bakes | AI Recipe Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Bakery Theme */
        body { background-color: #fdf8f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .brand-color { color: #d97706; }
        .btn-bake { background-color: #d97706; border: none; color: white; font-weight: bold; transition: 0.3s; }
        .btn-bake:hover { background-color: #b45f06; color: white; transform: translateY(-2px); }
        .recipe-card { border-top: 6px solid #d97706; border-radius: 12px; }
        .recipe-content h2, .recipe-content h3 { color: #d97706; margin-top: 1.5rem; }
        .recipe-content ul { padding-left: 1.5rem; }
        .recipe-content li { margin-bottom: 0.5rem; }
        /* Custom Bakery Scrollbar */
        .recipe-content::-webkit-scrollbar { width: 6px; }
        .recipe-content::-webkit-scrollbar-track { background: #fdf8f5; border-radius: 8px; }
        .recipe-content::-webkit-scrollbar-thumb { background: #d97706; border-radius: 8px; }
        .recipe-content::-webkit-scrollbar-thumb:hover { background: #b45f06; }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center mb-5">
                <h1 class="display-4 fw-bold brand-color">🍪 Ben Bakes</h1>
                <p class="lead text-muted">AI-Powered Custom Cookie Architect</p>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        
                        <form action="{{ route('recipe.generate') }}" method="POST" onsubmit="document.getElementById('bakeBtn').innerHTML = '👩‍🍳 Baking... Please wait'; document.getElementById('bakeBtn').disabled = true;">
                            @csrf
                            <div class="mb-4">
                                <label for="ingredients" class="form-label fw-bold fs-5">What's in your pantry?</label>
                                <textarea class="form-control form-control-lg bg-light" id="ingredients" name="ingredients" rows="3" placeholder="e.g., dark chocolate, sea salt, walnuts, brown butter..." required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" id="bakeBtn" class="btn btn-bake btn-lg py-3 shadow-sm">
                                    Whip Up My Custom Recipe
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @if(isset($recipe))
        <div class="row justify-content-center pb-5">
            <div class="col-md-10">
                <div class="card shadow recipe-card">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="text-center mb-4">✨ Fresh Out of the Oven</h2>
                        <hr class="mb-4">
                        
                        <div class="recipe-content fs-5">
                            {!! Str::markdown($recipe) !!}
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($savedRecipes) && $savedRecipes->count() > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <h3 class="text-center brand-color mb-4">📚 Ben's Recipe Vault</h3>
                <div class="row justify-content-center g-4">
                    @foreach($savedRecipes as $saved)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm recipe-card">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                    <span class="badge bg-secondary mb-2">Made with: {{ $saved->ingredients }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="recipe-content" style="max-height: 250px; overflow-y: auto; font-size: 0.9rem;">
                                        {!! Str::markdown($saved->recipe_text) !!}
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 text-muted small pb-3">
                                    Baked on {{ $saved->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

</body>
</html>