@extends('layouts.template')

@section('content')
<div class="container">
	<h2>Edit Flashcard</h2>
	<form action="{{ route('flashcard.update', $flashcard->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		@method('PUT')
		<div class="mb-3">
			<label for="title" class="form-label">Title</label>
			<input type="text" class="form-control" id="title" name="title" value="{{ old('title', $flashcard->title) }}" required>
		</div>
		<div class="mb-3">
			<label for="description" class="form-label">Description</label>
			<textarea class="form-control" id="description" name="description">{{ old('description', $flashcard->description) }}</textarea>
		</div>
		<div class="mb-3">
			<label for="document" class="form-label">Replace Document (optional)</label>
			<input type="file" class="form-control" id="document" name="document">
			@if($flashcard->document_path)
				<p>Current: <a href="{{ asset('storage/' . $flashcard->document_path) }}" target="_blank">View Document</a></p>
			@endif
		</div>
		<button type="submit" class="btn btn-primary">Update Flashcard</button>
		<a href="{{ route('flashcard.index') }}" class="btn btn-secondary">Cancel</a>
	</form>
</div>
@endsection
