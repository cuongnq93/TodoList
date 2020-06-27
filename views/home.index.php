<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="cuong.nq">
    <title>My Todo List</title>

    <link href="/dist/css/app.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <h1>My todo list</h1>
        <div class="top-btn mb-3">
            <div class="row">
                <div class="col-4">
                    <div class="btn-group" role="group" id="actionBtn">
                        <button type="button" class="btn btn-outline-primary" data-type="prev">Prev</button>
                        <button type="button" class="btn btn-outline-primary" data-type="today">Today</button>
                        <button type="button" class="btn btn-outline-primary" data-type="next">Next</button>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <button type="button" class="btn btn-primary" id="createNewTodoBtn">
                        Create new todo
                    </button>
                </div>
                <div class="col-4 text-right">
                    <div class="dropdown" id="changeViewBtn">
                        <button class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                            Month
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" data-type="day">Day</a>
                            <a class="dropdown-item" href="#" data-type="week">Week</a>
                            <a class="dropdown-item" href="#" data-type="month">Month</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="calendar"></div>
        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; Cuong.nq</p>
        </footer>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createNewTodo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create / update todo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" style="display: none"></div>
                    <form>
                        <div class="form-group">
                            <label for="workName">Name</label>
                            <input type="text" class="form-control" id="workName" name="work_name" placeholder="Meeting Project ABC">
                        </div>
                        <div class="form-group">
                            <label for="workDatetime">Start - End Date time</label>
                            <input type="text" class="form-control input-date" id="workDatetime">
                        </div>
                        <div class="form-group">
                            <label for="workStatus">Status</label>
                            <select class="custom-select" id="workStatus" name="status">
                                <option value="0" selected>Choose...</option>
                                <option value="1">Planning</option>
                                <option value="2">Doing</option>
                                <option value="3">Complete</option>
                            </select>
                        </div>

                        <input type="hidden" class="form-control" name="start_at" id="workStartAt">
                        <input type="hidden" class="form-control" name="end_at" id="workEndAt">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-delete" style="display: none;">
                        <span class="spinner-border spinner-border-sm" style="display: none"></span>
                        Delete
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-submit">
                        <span class="spinner-border spinner-border-sm" style="display: none"></span>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="/dist/js/app.min.js"></script>
</body>
</html>
