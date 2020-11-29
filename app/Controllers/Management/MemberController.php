<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use Respect\Validation\Validator as v;
//endregion

class MemberController extends Controller
{
    public function getIndex($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null,
            'pagination' => null,
            'table' => null
        ];
        //endregion

        try {

            //region Variables
            //region Paging
            $page = 0;
            $limit = 0;
            $skip = 0 ;
            $count = 0;
            $pagination = null;
            //endregion
            //region Table Variables
            $table = null;
            //endregion
            //endregion

            //region Paging
            $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
            $limit = 10;
            $skip = ($page - 1) * $limit;
            $count = $this->db->table('tbl_member')->count();
            
            $pagination = [
                'needed' => $count > $limit,
                'count' => $count,
                'page' => $page,
                'lastpage' => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
                'limit' => $limit,
            ];

            $table = $this->db->table('tbl_member')->skip($skip)->take($limit)->get();
            //endregion

            //region Set Return Data
            $returnData["pagination"] = $pagination;
            $returnData["table"] = $table;
            //endregion

        } catch (\Exception $e) {
            $this->logger->addError($e->getMessage());
            $this->flash->addMessage('error', $e->getMessage());
        }

        //region Render Page
        return $this->view->render($response, 'management/member/index.twig', $returnData);
        //endregion
    }

    public function postNew($request, $response)
    {
        try {
            $validation = $this->validator->validate($request, [
                'name' => v::notEmpty(),
                'mail' => v::noWhitespace()->notEmpty()->email()->ManagementEmailAvailable(),
                'password' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                return $response->withRedirect($this->router->pathFor('management-admin'));
            }

            $tblUser = TblMember::create([
                'name' => $request->getParam('name'),
                'mail' => $request->getParam('mail'),
                'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
            ]);

            $this->management->attempt($tblUser->mail, $request->getParam('password'));

            return $response->withRedirect($this->router->pathFor('management-member'));

        } catch (Exception $e) {
            $this->logger->addError($e->getMessage());

            return null;
        }
    }

    public function postSearch($request, $response)
    {
        try {
            $records = $this->db->table('tbl_member')->get();

            return $this->view->render($response, 'management/member/index.twig', ['records' => $records]);

        } catch (Exception $e) {
            $this->logger->addError($e->getMessage());

            return null;
        }
    }

    public function getEdit($request, $response)
    {
        try {


        } catch (Exception $e) {
            $this->logger->addError($e->getMessage());

            return null;
        }
    }

    public function postEdit($request, $response)
    {
        try {


        } catch (Exception $e) {
            $this->logger->addError($e->getMessage());

            return null;
        }
    }

    public function getDelete($request, $response)
    {
        try {


        } catch (Exception $e) {
            $this->logger->addError($e->getMessage());

            return null;
        }
    }
}