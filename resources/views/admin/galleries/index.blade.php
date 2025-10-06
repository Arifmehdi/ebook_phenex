@extends('admin.master')
@section('title',"Admin Dashboard | All Galleries")

@section('body')
    <section class="pt-5">
        <div class="card">
            <div class="card-header bg-card">
                <div class="card-title">All Galleries</div>
            </div>
            <div class="card-body">
                 <div class="table-responsive">
                    <table class="table table-borderd table-sm">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Image</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = (($galleries->currentPage() - 1) * $galleries->perPage() + 1); ?>
                            @forelse ($galleries as $gallery)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>

                                    <td class="d-flex">
                                    <a href="{{route('galleries.edit',$gallery)}}" class="text-success mr-2"><i class="fas fa-edit"></i></a>

                                        <form action="{{route('galleries.destroy', $gallery) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button href="{{route('galleries.destroy', $gallery)}}" class="text-danger" onclick="return confirm('Are you sure? you want to delete this gallery Item?')" style="all:unset; cursor: pointer;"><i class="fas fa-trash"></i></button>
                                        </form>

                                    </td>
                                    <td>{{ $gallery->title }}</td>
                                    <td>{{ Str::limit($gallery->designation,50) }}</td>

                                    <td>
                                        <img src="{{ route('imagecache', [ 'template'=>'sbixs','filename' => $gallery->fi() ]) }}" alt="">
                                    </td>
                                    <td>
                                        @if ($gallery->active)
                                        <span class="badge badge-primary">Active</span>
                                        @endif

                                    </td>
                                 
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-danger h5 text-center">No gallery Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $galleries->links() }}
                 </div>
            </div>
        </div>
    </section>
@endsection

@push('js')

@endpush
