<?php 
namespace App\Http\Controllers\api;

use App\Blogs;
use App\User;
use Redirect;
use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use Validator;

use Illuminate\Http\Request;

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function show(Request $request)
	{

		$input              = $request->all();
        $id                 = isset($input['id']) ? $input['id'] : null;

        if($id != null)
        {
        	 $model = Blogs::paginate($key);
        }
        else
        {
        	$model = Blogs::find($id);
        }

       	$return = array(
       		'status_code' => 200, 
       		'message' => 'success'
       		'body' => array( 'article' => $model)
       		);

        return Response::json( $product );

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email_address' => 'required'.
			'password' => 'required',
			'title' => 'required',
			'slug' => 'required',
			'content' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(array('status_code' => 400, 'message' => $validator->messages(), 'body' => array('result' => false)));
		}

		$userModel = User::where('email_address', $request->input('email_address'))->first();
		if ($userModel == null) {
			return response()->json(array('status_code' => 400, 'message' => 'invalid user', 'body' => array('result' => false) ));
		}

		$user_id = $userModel->id;

		$model = new Blogs();
		$model->title = $request->input('title');
		$model->slug = $request->input('slug');
		$model->content = $request->input('content');
		$model->user_id = $user_id;
		$model->save();

		return Response::json(array(
			'status_code' => 200,
			'message' => 'Success',
			'body' => array(
				'result' => true,
				'blog_id' => $model->id) 
			));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$blog_id)
	{
		$validator = Validator::make($request->all(), [
			'email_address' => 'required'.
			'password' => 'required'
		]);

		if ($blog_id) {
			return response()->json(array('status_code' => 400,'message' => 'blog_id is required','body' => array('result' => false)  ));
		}

		if ($validator->fails()) {
			return response()->json(array('status_code' => 400, 'message' => $validator->messages(), 'body' => array('result' => false) ));
		}

		if ( !isset($request->input('title')) || !isset($request->input('slug')) || !isset($request->input('content')) )
		{
			return response()->json(array('status_code' => 400, 'message' => 'Titlt or Content or Slug must exist.', 'body' => array('result' => false)));
		}

		$userModel = User::where('email_address', $request->input('email_address'))->first();
		if ($userModel == null) {
			return response()->json(array('status_code' => 400,'message' => 'invalid user'), 'body' => array('result' => false));
		}

		$user_id = $userModel->id;
		
		$model = Blogs::find($request->input('blog_id'));
		if($request->input('title',null) !== null) $model->title  = $request->input('title');
		if($request->input('slug',null) !== null) $model->slug  = $request->input('slug');
		if($request->input('content',null) !== null) $model->content  = $request->input('content');
		$model->save();

		return Response::json(array(
			'status_code' => 200,
			'message' => 'success',
			'body' => array('result' => true, 'blog_id' => $model->id)
			));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'email_address' => 'required'.
			'password' => 'required',
			'blog_id' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(array('status_code' => 400, 'message' => $validator->messages(), 'body' => array('result' => false) ));
		}

		$userModel = User::where('email_address', $request->input('email_address'))->first();
		if ($userModel == null) {
			return response()->json(array('status_code' => 400, 'message' => 'invalid user', 'body' => array('result' => false) ));
		}
		
		$model = Blogs::find($request->input('blog_id'));
		$model->delete();

		return Response::json(array(
			'status_code' => 200,
			'message' => 'success',
			'body' => array('result' => true)
			));
	}
}
