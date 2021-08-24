<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpine JS CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div class="container mt-5" x-data="crudAlpine()" x-init="initialize">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        Employees
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input x-ref="nameInput" x-model="name" class="form-control" aria-describedby="helpId" type="text" name="name">
                            <small id="helpId1" class="form-text">
                                Type the name of the employee
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input x-ref="emailInput" x-model="email" type="email" class="form-control" name="email" id="email" aria-describedby="helpId2">
                            <small id="helpId2" class="form-text text-muted">
                                Type the email of the employee
                            </small>
                        </div>

                        <div class="btn-group" role="group" aria-label="x">
                            <button type="button" x-ref="btnAdd" class="btn btn-success" x-on:click="action(`?create=1`)">
                                Add
                            </button>
                            <button type="button" x-ref="btnUpdate" class="btn btn-warning" x-on:click="action(`?update=1`)">
                                Update
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        Footer
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="employee in employees" :key="employee.id">
                            <tr>
                                <td x-text="employee.id" scope="row"></td>
                                <td x-text="employee.name"></td>
                                <td x-text="employee.email"></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" @click="selectEmployee(employee)" class="btn btn-outline-info">View</button>
                                        <button type="button" @click="deleteEmployee(employee)" class="btn btn-outline-danger">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        'use strict';
        function crudAlpine() {
            return {
                id: 0,
                name: '',
                email: '',
                url: 'crud.php',
                employees: [],
                loadedData: false,
                initialize: function() {
                    this.read();

                    this.$refs.btnAdd.disabled = false;
                    this.$refs.btnUpdate.disabled = true;

                    this.id = '';
                    this.name = '';
                    this.email = '';

                    this.$watch('name', _ => {
                        this.$refs.nameInput.classList.remove('has-danger');
                    });

                    this.$watch('email', _ => {
                        this.$refs.emailInput.classList.remove('has-danger');
                    });
                },
                action: function(actionUrl) {
                    if (this.validateInputs()) {
                        let dataToSend = {
                            method: 'POST',
                            body: JSON.stringify({
                                id: this.id,
                                name: this.name,
                                email: this.email
                            })
                        };

                        this.requestHandler(`${actionUrl}`, dataToSend);
                    } else {
                        console.error(`Los datos están vacíos!`);
                    }

                    this.read();
                },
                read: function() {
                    fetch(this.url)
                        .then(r => r.json())
                        .then((employeesData) => {
                            this.loadedData = (employeesData[0].id != undefined);

                            if (this.loadedData === true) {
                                this.employees = employeesData;
                            }
                        })
                        .catch(err => console.log(err));
                },
                selectEmployee: function(employee) {
                    console.log(`View employee ${employee.name}`);

                    this.$refs.btnAdd.disabled = true;
                    this.$refs.btnUpdate.disabled = false;

                    this.id = employee.id;
                    this.name = employee.name;
                    this.email = employee.email;
                },
                deleteEmployee: function(employee) {
                    console.log(employee);
                    this.requestHandler(`?delete=${employee.id}`, null)
                },
                requestHandler: function(urlAction, methods) {
                    fetch(`${this.url}${urlAction}`, methods)
                        .then(r => r.json())
                        .then(dataEmployee => {
                            console.log(dataEmployee);
                            this.initialize();
                        })
                        .catch(err => console.log(err))
                },
                validateInputs: function () {
                    if ((this.name != '') && (this.email != '')) {
                       return true;
                    } else {
                        this.$refs.nameInput.classList.add('is-invalid');
                        this.$refs.emailInput.classList.add('is-invalid');
                    }
                }
            }
        }
    </script>
</body>

</html>