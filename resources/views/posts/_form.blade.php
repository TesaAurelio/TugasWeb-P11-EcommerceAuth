@php($post = $post ?? null)

<div>
    <x-input-label for="title" value="Judul" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                  :value="old('title', $post?->title)" required />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div>
    <x-input-label for="body" value="Isi" />
    <textarea id="body" name="body" rows="8" required
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('body', $post?->body) }}</textarea>
    <x-input-error :messages="$errors->get('body')" class="mt-2" />
</div>

<div class="flex items-center gap-2">
    <input type="hidden" name="is_published" value="0">
    <input id="is_published" type="checkbox" name="is_published" value="1"
           class="rounded border-gray-300 text-indigo-600 shadow-sm"
           @checked(old('is_published', $post?->is_published))>
    <label for="is_published" class="text-sm text-gray-700">Terbitkan (jika tidak dicentang = draft)</label>
</div>
