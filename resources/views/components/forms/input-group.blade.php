@props([
"value"=>"",
"name"=>"",
"required"=>true,
"type"=>"text",
"placeholder"=>''
])
<x-label for="{{$name}}" class="block text-gray-700 text-sm font-bold mb-2"/>
<x-input name="{{$name}}" :required="$required" :type="$type" :placeholder="$placeholder"
         class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
         value="{{$value}}"/>
<x-error field="{{$name}}" class="text-red-500 text-sm italic"/>