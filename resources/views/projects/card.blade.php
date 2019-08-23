<div class="card  flex flex-col " style="height: 200px;">
		<h3 class="font-normal text-xl py-4 -ml-5 border-l-4  border-blue pl-4">{{$project->title}}</h3>
		<div  class=" text-gray flex-1">{{ str_limit($project->description, 20)}}</div>
		@can('manage', $project)
			<footer>
					<form action="{{$project->path()}}" method="POST" class="text-right">
							@csrf
							@method('DELETE')
							<button type="submit"class="text-xs ">delete</button>
					</form>
			</footer>
		@endcan
</div>