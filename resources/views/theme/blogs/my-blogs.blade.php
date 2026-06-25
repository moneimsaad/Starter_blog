@extends('theme.master')
@section('title', 'My Blogs')


@section('contant')
  @include('theme.partials.hero', ['title' => 'My Blogs'])

    <!-- ================ contact section start ================= -->
    <section class="section-margin--small section-margin">
      <div class="container">
        <div class="row">
          <div class="col-12">
            @if (session('blogDeleteStatus'))
                <div class="alert alert-success">
                  {{ session('blogDeleteStatus') }}
                </div>
            @endif
            <table class="table table-bordered" >
              <thead>
                <tr>
                  <th scope="col" >Title</th>
                  <th scope="col" >Category</th>
                  <th scope="col" >Description</th>
                  <th scope="col" >Comments</th>
                  <th scope="col" >Actions</th>
                </tr>
              </thead>
              <tbody>
                @if (count($blogs) > 0)
                  @foreach ($blogs as $blog)
                    <tr>
                      <td><a href="{{ route('blogs.show', $blog) }}">{{ $blog->name }}</a></td>
                      <td>{{ $blog->category->name }}</td>
                      <td>{{ Str::substr($blog->description, 0, 25) . '...' }}</td>
                      <td>{{ $blog->comments->count() }}</td>
                      <td>
                        <div class="d-flex">
                          <a href="{{ route('blogs.show', $blog) }}" class="btn btn-success m-1" target="_blank">Show</a>
                          <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary m-1">Edit</a>
                          <form action="{{ route('blogs.destroy', $blog) }}" method="POST" id="delete_form">
                            @method('DELETE')
                            @csrf
                            <a href="javascript:$('form#delete_form').submit();" class="btn btn-danger m-1">Delete</a>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
            @if (count($blogs) > 0)
              {{ $blogs->render('pagination::bootstrap-5') }}
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- ================ contact section end ================= -->

@endsection