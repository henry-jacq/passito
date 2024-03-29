<h3 class="lead fs-4"><?= $title ?></h3>
<hr>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="col-md-2">
            <div class="form-group mb-3">
                <label for="requestPerPage" class="form-label">Requests per page</label>
                <select class="form-select form-select-sm" id="requestPerPage" aria-label="Default select example">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>
        </div>
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th scope="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                        </div>
                    </th>
                    <th scope="col"># REQ_ID</th>
                    <th scope="col">Digital ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <th scope="row">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                        </div>
                    </th>
                    <td>1</td>
                    <td>2212005</td>
                    <td>Jack</td>
                    <td>
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                        </div>
                    </th>
                    <td>2</td>
                    <td>2212023</td>
                    <td>Henry</td>
                    <td>
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>