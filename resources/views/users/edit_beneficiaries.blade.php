<div class="modal fade" id="editEmpBeneficiaries" tabindex="-1" role="dialog" aria-labelledby="editBeneficiariesLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editBeneficiariesLabel">Edit Beneficiaries</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div id="app">
                <form method='POST' action='updateBeneficiariesHR/{{ $user->id }}' @submit.prevent="submit" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        
                            <div class="form-card">
                                <button type="button" class="btn btn-primary btn-sm mb-2" style="float: right;" @click="addBeneficiary()">Add Row</button>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    First Name
                                                </th>
                                                <th class="text-center">
                                                    Last Name
                                                </th>
                                                <th class="text-center">
                                                    Middle Name
                                                </th>
                                                <th class="text-center">
                                                    Gender
                                                </th>
                                                <th class="text-center">
                                                    Date of birth
                                                </th>
                                                <th class="text-center">
                                                    Relationship
                                                </th>
                                                <th class="text-center">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row,index) in beneficiaries" v-bind:key="index">
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.first_name"  placeholder="Firt Name" required>     
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.last_name"  placeholder="Last Name" required>     
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.middle_name"  placeholder="Middle Name">     
                                                </td>
                                                <td>
                                                    <select data-placeholder="Gender" class="form-control form-control-sm" v-model="row.gender">
                                                        <option value="">--Select Gender--</option>
                                                        <option value="MALE">MALE</option>
                                                        <option value="FEMALE">FEMALE</option>
                                                    </select>  
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control" v-model="row.bdate" required>     
                                                </td>
                                                <td>
                                                    <select class="form-control" v-model="row.relation" id="relation" required>
                                                        <option value="">Choose Relationship</option>
                                                        <option value="MOTHER">MOTHER</option>
                                                        <option value="FATHER">FATHER</option>
                                                        <option value="BROTHER">BROTHER</option>
                                                        <option value="SISTER">SISTER</option>
                                                        <option value="SPOUSE">SPOUSE</option>
                                                        <option value="CHILD">CHILD</option>
                                                    </select>     
                                                </td>
                                                <td>
                                                    <button v-if="row.id" type="button" class="btn btn-danger btn-sm mt-2" style="float:right;"  @click="removeBeneficiary(index,row)">x</button>
                                                    <button v-else type="button"  class="btn btn-success btn-sm mt-2" style="float:right;" @click="removeBeneficiary(index)">x</button> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    
                    </div>    
                    <div class="modal-footer">

                        <div class="row">
                            <div class="col-md-12">
                                <input type="checkbox" id="privacy-beneficiaries-check" class="ml-1 form-check-input">
                                <label class='ml-4 mb-0' for='same_as'><small><i> I hereby declare that the information provided is true and correct.  I also understand that any willful misrepresentation may result to disciplinary action.</i></small></label>
                            </div>
                            <div class="col-md-12">
                                <input type="checkbox" id="privacy-beneficiaries" disabled class="ml-1 form-check-input">
                                <label class='ml-4 mb-0' for='same_as'><small><i>By submitting this form, the Employee hereby explicitly and unambiguously consents to the collection, use and transfer, in electronic or other form, of the Employee’s personal data as described in this form and any other materials by and among, as applicable, the Company, its Subsidiaries or Affiliates, and the Employer for the exclusive purpose of implementing, administering and managing the Employee’s employment.</i></small></label>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="submit-beneficiaries-btn" disabled class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>

<script>
    var app = new Vue({
            el: '#app',
            data : {
                beneficiaries : [],
                deletedBeneficiaries : [],
            },
            created () {
                this.fetchBeneficiaries();
            },
            methods: {
                fetchBeneficiaries(){
                    axios.get('/account-setting-hr/getBeneficiariesHR/'+{{ $user->id }})
                    .then(res => {
                        if (res.data.length > 0) {
                            var dependents_arr = [];
                            res.data.forEach(element => {
                                this.beneficiaries.push({
                                    id: element.id,
                                    first_name: element.first_name,
                                    last_name: element.last_name,
                                    middle_name: element.middle_name,
                                    gender: element.gender,
                                    bdate: element.bdate,
                                    relation: element.relation
                                });
                            });
                        }
                    })
                },
                submit(){
                    axios.post('/account-setting-hr/updateBeneficiariesHR/'+{{ $user->employee->id }},{
                        beneficiaries: JSON.stringify(this.beneficiaries),
                        deleted_beneficiaries: JSON.stringify(this.deletedBeneficiaries),
                    })
                    .then(res => {
                        swal("Successfully Updated",{icon:"success"})
                        .then(function() {
                            location.reload();
                        });
                    })
                },
                addBeneficiary(){
                    this.beneficiaries.push({
                        id: "",
                        first_name: "",
                        middle_name: "",
                        last_name: "",
                        gender: "",
                        bdate: "",
                        relation: "",
                    });
                },
                removeBeneficiary: function(index,beneficiary) {
                    if(beneficiary){
                        swal({
                            title: 'Are you sure you want to remove this Beneficiary?',
                            icon: 'warning',
                            buttons: true,
					        dangerMode: true,
                            }).then((result) => {
                            if (result) {
                                this.deletedBeneficiaries.push({
                                    id: beneficiary.id
                                });
                                this.beneficiaries.splice(index, 1);    
                            }
                        })
                    }else{
                        this.beneficiaries.splice(index, 1);
                    } 
                },
            }
    });
</script>
