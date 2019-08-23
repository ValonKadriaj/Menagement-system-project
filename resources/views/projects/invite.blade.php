<div class="card flex flex-col mt-3" >
		<h3 class="font-normal text-xl py-4 -ml-5 border-l-4  border-blue pl-4">
				Invite User
		</h3>
		<form action="{{$project->path(). '/invitations'}}" method="POST">
				@csrf
				<div class="mb-3">
						<input type="email" name="email" class="border border-gray-500 rounded w-full py-1 outline-none" placeholder="Email">
				</div>
				<button type="submit"class="button">Invite</button>
		</form>
		@include('projects.errors', ['bag'=>'invitations'])
</div>