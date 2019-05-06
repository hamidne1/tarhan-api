<?php

namespace App\Http\Controllers;

use App\Exceptions\FileExistsException;
use App\Services\MediaManagerService;
use Illuminate\Validation\ValidationException;

class MediaManagerController extends Controller {

    /**
     * @var MediaManagerService
     */
    protected $service;

    /**
     * FileManagerController constructor.
     * @param MediaManagerService $service
     */
    public function __construct(MediaManagerService $service)
    {
        $this->service = $service;
    }

    /**
     * showing directory data
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index()
    {
        return response()->json([
            'data' => $this->service->index(),
            'home' => request()->get('directory')
        ]);
    }


    /**
     * destroy folder or file from storage
     *
     * @param $passedFolders
     * @param $objName
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete()
    {
        $this->service->delete();

        return $this->respondDeleted();
    }


    /**
     * create new folder into given directory storage
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function create()
    {
        try {
            return $this->service->create();

        } catch (FileExistsException $e) {
            return $this->respondInternalError('فایل با نام مشابه وجود دارد');
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Exception $e) {

            return $this->respondInternalError('مشکلی به وجود آمده است');
        }
    }

    /**
     * store images
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \App\Exceptions\DirectoryNotExists
     */
    public function upload()
    {
        $data = $this->service->upload();

        return $this->respond('تصویر جدید بارگزاری شد', $data);
    }


}
