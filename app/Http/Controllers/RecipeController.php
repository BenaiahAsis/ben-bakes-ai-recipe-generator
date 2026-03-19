<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Recipe;

class RecipeController extends Controller
{
    // Shows the starting page AND the recipe history
    public function index()
    {
        // Fetch the 5 most recently saved recipes to show on the page
        $savedRecipes = Recipe::latest()->take(5)->get();
        return view('recipe', ['savedRecipes' => $savedRecipes]);
    }

    // Handles the AI generation
    public function generate(Request $request)
    {
        // 1. Validate the user input
        $request->validate([
            'ingredients' => 'required|string|max:500',
        ]);

        // 2. Prepare the API Key
        $apiKey = env('OPENAI_API_KEY');

        // 3. The Professional & Strict Prompt
        $strictPrompt = "You are an expert baker for 'Ben Bakes'. Your challenge is to create a unique, delicious cookie or treat strictly using ONLY the following ingredients: " . $request->ingredients . ". 
        
        CRITICAL RULES:
        1. You MUST NOT add any unlisted ingredients. Do not add water, eggs, flour, sugar, butter, baking soda, or anything else unless it is explicitly listed by the user.
        2. If it is physically impossible to make any kind of edible treat with ONLY the provided ingredients, you must reject the request. 
        3. FORMAT FOR REJECTIONS: Keep your rejection professional, concise, and straight to the point. Do not use overly theatrical greetings. Explain why it won't work in strictly 2 sentences or less. Then, on a new line, tell the user the exact 1 or 2 bare minimum ingredients they need to add to make a successful treat.";

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $strictPrompt]
                    ]
                ]
            ]
        ]);

        // 4. Check if the request worked
        if ($response->successful()) {
            $data = $response->json();
            $recipeText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, the oven is acting up. Try again!';
            
            // ---> THE DATABASE MAGIC IS RIGHT HERE <---
            // Save it to MySQL only if it successfully generated
            Recipe::create([
                'ingredients' => $request->ingredients,
                'recipe_text' => $recipeText
            ]);

        } else {
            $recipeText = "Error: " . $response->body();
        }

        // Fetch the updated history to show on the page
        $savedRecipes = Recipe::latest()->take(5)->get();

        // 5. Return to the view with the new recipe AND the history
        return view('recipe', [
            'recipe' => $recipeText, 
            'savedRecipes' => $savedRecipes
        ]);
    }
}