@extends('layouts.template')

@section('content')
<div class="container">
	<h2>Flashcard Details</h2>
	<div class="mb-3">
		<strong>Title:</strong> {{ $flashcard->title }}
	</div>
	<div class="mb-3">
		<strong>Description:</strong> {{ $flashcard->description }}
	</div>
	<div class="mb-3">
		<strong>Status:</strong> {{ $flashcard->status }}
		@if($flashcard->status === 'processing')
			<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
			<span>Generating flashcards, please wait...</span>
		@elseif($flashcard->status === 'failed')
			<span class="text-danger">Generation failed. Please try again.</span>
		@elseif($flashcard->status === 'completed')
			<span class="text-success">Generation completed!</span>
		@endif
	</div>
	<div class="mb-3">
		<strong>Document:</strong>
		@if($flashcard->document_path)
			<a href="{{ asset('storage/' . $flashcard->document_path) }}" target="_blank">View Document</a>
		@else
			N/A
		@endif
	</div>
	<div class="mb-3">
		<strong>Created At:</strong> {{ $flashcard->created_at }}
	</div>

	<h4>Generated Flashcards</h4>
	@if(is_array($flashcard->cards) && count($flashcard->cards))
		<ul class="list-group mb-3">
			@foreach($flashcard->cards as $card)
				<li class="list-group-item">
					<strong>Q:</strong> {{ $card['question'] ?? '' }}<br>
					<strong>A:</strong> {{ $card['answer'] ?? '' }}
				</li>
			@endforeach
		</ul>
	@else
		<p>No flashcards generated yet.</p>
	@endif

	<a href="{{ route('flashcard.index') }}" class="btn btn-secondary">Back to List</a>
	<a href="{{ route('flashcard.edit', $flashcard->id) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
