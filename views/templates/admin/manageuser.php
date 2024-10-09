<div class="container my-5">
    <h4 class="fw-normal mb-4">Manage Users</h4>
    <hr>

    <div class="row">
        <!-- Add User Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add User</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" required>
                                <option selected disabled value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add User</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Student Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Student</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="studentName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="studentName" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentDigitalId" class="form-label">Digital ID</label>
                            <input type="text" class="form-control" id="studentDigitalId" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentYear" class="form-label">Year</label>
                            <input type="number" class="form-control" id="studentYear" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentDegree" class="form-label">Degree</label>
                            <input type="text" class="form-control" id="studentDegree" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentBranch" class="form-label">Branch</label>
                            <input type="text" class="form-control" id="studentBranch" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentRoomNo" class="form-label">Room No</label>
                            <input type="text" class="form-control" id="studentRoomNo" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentParentNo" class="form-label">Parent No</label>
                            <input type="text" class="form-control" id="studentParentNo" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentInstitution" class="form-label">Institution</label>
                            <input type="text" class="form-control" id="studentInstitution" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>