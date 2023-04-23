<script>
	var StudentData = [
		{
			ST_ID     			: "TYPE1",
			ST_FN    			: "Noel Lehitimas",
			ST_LN      			: "100001",
			ST_DOB      		: "EXT_PARTNER1",
			ST_G     			: "SOURCESYS1",
			ST_CI    			: " ",
			ST_EC				: " ",
			ST_N				: " ",
			ST_E				: " ",
			ST_ES				: " ",
			ST_AR				: " ",
			ST_ACR				: " ",
			ST_PS				: " ",
			ST_FA				: " ",
			ST_GD				: " "

		},
		{
			ST_ID     			: "TYPE2",
			ST_FN    			: "Noel Lehitimas2",
			ST_LN      			: "100002",
			ST_DOB      		: "EXT_PARTNER2",
			ST_G     			: "SOURCESYS2",
			ST_CI    			: " ",
			ST_EC				: " ",
			ST_N				: " ",
			ST_E				: " ",
			ST_ES				: " ",
			ST_AR				: " ",
			ST_ACR				: " ",
			ST_PS				: " ",
			ST_FA				: " ",
			ST_GD				: " "
		}
	];

	const StudentDataOrganizer = {
		_filteredById : function(id){
			filteredST = [];
			for(let i=0; i<StudentData.length; i++){
				if(StudentData[i].BIZPART_ID == id){
					filteredBP.push(StudentData[i]);
				}
			}
			return filteredBP;
		},
		_updateById : function(id){
			let busyDialog = showBusyDialog("Please wait loading..");
				busyDialog.open();
			
			StudentData.map(Stud_id => {
				if (Stud_id.ST_ID == id) {
				 
						Stud_id.ST_ID			= ui("STUDENT_ID").getValue().trim();
						Stud_id.ST_FN 			= ui("FIRST_NAME").getValue().trim();
        				Stud_id.ST_LN 			= ui("LAST_NAME").getValue().trim();
						Stud_id.ST_DOB 			= ui("DATE_OF_BIRTH").getValue().trim();
        				Stud_id.ST_G  			= ui("GENDER").getValue().trim();
        				Stud_id.ST_CI 			= ui("CONTACT_INFO").getValue().trim();
        				Stud_id.ST_EC 			= ui("EM_CONT").getValue().trim();
        				Stud_id.ST_N  			= ui("NATIONALITY").getValue().trim();
        				Stud_id.ST_E  			= ui("ETHNICITY").getValue().trim();
        				Stud_id.ST_ES 			= ui("ENROLL_STAT").getValue().trim();
        				Stud_id.ST_AR 			= ui("ATT_REC").getValue().trim();
        				Stud_id.ST_ACR 			= ui("ACAD_REC").getValue().trim();
        				Stud_id.ST_PS 			= ui("PROG_STUD").getValue().trim();
        				Stud_id.ST_FA 			= ui("FINAN_AID").getValue().trim();
        				Stud_id.ST_GD 			= ui("GRAD_DATE").getValue().trim();
				}
				
			});
			screenMode._display(id);
			listingStudent._getData(StudentData);
			setTimeout(() => {busyDialog.close();}, 2000);
		},
		
		/*_getRadioIndex : function(id){
			let radioButton = ui("BP_COMPANY").getButtons();
			let selectedIndex;
			for(let i=0; i<radioButton.length; i++){
				if(radioButton[i].getId() == id){
					selectedIndex = i;
				}
			}

			return selectedIndex;

		},*/

		_validateSTUD : function(id){
			let isExist = false;
			for(let i=0; i<StudentData.length; i++){
				if(StudentData[i].ST_ID == id){
					isExist = true;
					break;
				}
			}
			return isExist;
		}
	}

	const screenMode = {
		_id : "",
		_title : "",
		_mode : "",
		_create : function(){
			this._mode = "create";
			let stud_title = this._title;
			stud_title = "Add New Student";
			this._clear();
			//Buttons
			ui("CREATE_STUDENT_SAVE_BTN").setVisible(true);
			ui("CREATE_STUDENT_EDIT_BTN").setVisible(false);
			ui("CREATE_STUDENT_CANCEL_BTN").setVisible(false);

			//title and crumbs
			//ui('STUDENT_TITLE').setText(stud_title);
			ui('CREATE_STUDENT_BRDCRMS').setCurrentLocationText(stud_title);
			ui("PANEL_FORM").setTitle("New Student");

			//Fields
			ui('STUDENT_ID').setEditable(true);
			ui('FIRST_NAME').setEditable(true);
			ui('LAST_NAME').setEditable(true);
			ui('DATE_OF_BIRTH').setEditable(true);
			ui('GENDER').setEditable(true);
			ui('CONTACT_INFO').setEditable(true);
			ui('EM_CONT').setEditable(true);
			ui('NATIONALITY').setEditable(true);
			ui('ETHNICITY').setEditable(true);
			ui('ENROLL_STAT').setEditable(true);
			ui('ATT_REC').setEditable(true);
			ui('ACAD_REC').setEditable(true);
			ui('PROG_STUD').setEditable(true);
			ui('FINAN_AID').setEditable(true);
			ui('GRAD_DATE').setEditable(true);

			go_App_Right.to('CREATE_STUDENT_PAGE');
		},
		_edit : function(){
			this._mode = "edit";
			//Buttons
			ui("CREATE_STUDENT_SAVE_BTN").setVisible(true);
			ui("CREATE_STUDENT_EDIT_BTN").setVisible(false);
			ui("CREATE_STUDENT_CANCEL_BTN").setVisible(true);

			//Fields
			ui('STUDENT_ID').setEditable(false);
			ui('FIRST_NAME').setEditable(true);
			ui('LAST_NAME').setEditable(true);
			ui('DATE_OF_BIRTH').setEditable(true);
			ui('GENDER').setEditable(true);
			ui('CONTACT_INFO').setEditable(true);
			ui('EM_CONT').setEditable(true);
			ui('NATIONALITY').setEditable(true);
			ui('ETHNICITY').setEditable(true);
			ui('ENROLL_STAT').setEditable(true);
			ui('ATT_REC').setEditable(true);
			ui('ACAD_REC').setEditable(true);
			ui('PROG_STUD').setEditable(true);
			ui('FINAN_AID').setEditable(true);
			ui('GRAD_DATE').setEditable(true);
		},
		_display : function(id){
			ui('MESSAGE_STRIP_STUD_ERROR').destroyContent().setVisible(false);
			ui('STUDENT_ID').setValueState("None").setValueStateText("");
			this._mode = "display";
			this._id = id;
			let stud_title = this._title;
			stud_title = "Display Students";
			//Buttons
			ui("CREATE_STUDENT_SAVE_BTN").setVisible(false);
			ui("CREATE_STUDENT_EDIT_BTN").setVisible(true);
			ui("CREATE_STUDENT_CANCEL_BTN").setVisible(false);


			//fields with value
			let data = StudentDataOrganizer._filteredById(id);
			if(data.length > 0){
				ui('STUDENT_ID').setValue(data[0].ST_ID).setEditable(false);
       			ui('FIRST_NAME').setValue(data[0].ST_FN).setEditable(false);
        		ui('LAST_NAME').setValue(data[0].ST_LN).setEditable(false);
				ui('DATE_OF_BIRTH').setValue(data[0].ST_DOB).setEditable(false);
				ui('GENDER').setValue(data[0].ST_G).setEditable(false);
				ui('CONTACT_INFO').setValue(data[0].ST_CI).setEditable(false);
				ui('EM_CONT').setValue(data[0].ST_EC).setEditable(false);
				ui('NATIONALITY').setValue(data[0].ST_N).setEditable(false);
				ui('ETHNICITY').setValue(data[0].ST_E).setEditable(false);
				ui('ENROLL_STAT').setValue(data[0].ST_ES).setEditable(false);
				ui('ATT_REC').setValue(data[0].ST_AR).setEditable(false);
				ui('ACAD_REC').setValue(data[0].ST_ACR).setEditable(false);
				ui('PROG_STUD').setValue(data[0].ST_PS).setEditable(false);
				ui('FINAN_AID').setValue(data[0].ST_FA).setEditable(false);
				ui('GRAD_DATE').setValue(data[0].ST_GD).setEditable(false);
			
			
				//title and crumbs
				//ui('STUDENT_TITLE').setText(stud_title);
				ui('CREATE_STUDENT_BRDCRMS').setCurrentLocationText(stud_title);
				ui("PANEL_FORM").setTitle("Student ID "+"("+data[0].ST_ID+")");

				go_App_Right.to('CREATE_STUDENT_PAGE');
			}			
		},
		_clear : function(){
			ui('MESSAGE_STRIP_STUD_ERROR').destroyContent().setVisible(false);
			ui('STUDENT_ID').setValue("");
       		ui('FIRST_NAME').setValue("");
        	ui('LAST_NAME').setValue("");
			ui('DATE_OF_BIRTH').setValue("");
			ui('GENDER').setValue("");
			ui('CONTACT_INFO').setValue("");
			ui('EM_CONT').setValue("");
			ui('NATIONALITY').setValue("");
			ui('ETHNICITY').setValue("");
			ui('ENROLL_STAT').setValue("");
			ui('ATT_REC').setValue("");
			ui('ACAD_REC').setValue("");
			ui('PROG_STUD').setValue("");
			ui('FINAN_AID').setValue("");
			ui('GRAD_DATE').setValue("");
		}
	};

    const createSTUD = () => {
		let busyDialog = showBusyDialog("Please wait loading..");
		busyDialog.open();
		let data_for_general = {
			ST_ID:  ui("STUDENT_ID").getValue().trim(),
        	ST_FN:  ui("FIRST_NAME").getValue().trim(),
        	ST_LN:  ui("LAST_NAME").getValue().trim(),
        	ST_DOB: ui("DATE_OF_BIRTH").getValue().trim(),
        	ST_G:   ui("GENDER").getValue().trim(),
        	ST_CI:  ui("CONTACT_INFO").getValue().trim(),
        	ST_EC:  ui("EM_CONT").getValue().trim(),
        	ST_N :  ui("NATIONALITY").getValue().trim(),
        	ST_E :  ui("ETHNICITY").getValue().trim(),
        	ST_ES:  ui("ENROLL_STAT").getValue().trim(),
        	ST_AR:  ui("ATT_REC").getValue().trim(),
        	ST_ACR: ui("ACAD_REC").getValue().trim(),
        	ST_PS:  ui("PROG_STUD").getValue().trim(),
        	ST_FA:  ui("FINAN_AID").getValue().trim(),
        	ST_GD:  ui("GRAD_DATE").getValue().trim(),
   		};
		//add new data to array
		StudentData.push(data_for_general);
		screenMode._display(data_for_general.ST_ID);
		setTimeout(() => {busyDialog.close();}, 2000);
		
		//commented use for backend only
		/*fetch('/bizpartner/create_data',{
			method : 'POST',
			headers : {
				'Content-Type' : 'application/json',
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			body : JSON.stringify(data_for_general)


		}).then((response) => {
			console.log(response);
			return response.json();
		}).then(data => {
			console.log(data);
		}).catch((err) => {
			console.log("Rejected "+err);
		});*/
        
    }

	const displayStudent =  {
		
		_get_data(search){
			
			let busyDialog = showBusyDialog("Please wait loading..");
				busyDialog.open();

				let data = StudentDataOrganizer._filteredById(search);
				this._bind_data(data);
			
			
			setTimeout(() => {busyDialog.close();}, 2000);
		},
		_bind_data(data){
		
			ui("DISPLAY_STUDENT_TABLE").unbindRows();
			
			var lt_model = new sap.ui.model.json.JSONModel();
				lt_model.setSizeLimit(data.length);
				lt_model.setData(data);
				
			ui('DISPLAY_STUDENT_TABLE').setModel(lt_model).bindRows("/");
			ui("DISPLAY_STUDENT_TABLE").setVisible(true);
			
			ui('DISPLAY_STUDENT_TABLE_LABEL').setText("List (" + data.length + ")");
			//fn_clear_table_sorter("DISPLAY_BP_TABLE");
			
		}		
	};

	const listingStudent = {
		_getData : function(data){
			ui("STUDENT_LISTING_TABLE").unbindRows();
			
			var lt_model = new sap.ui.model.json.JSONModel();
				lt_model.setSizeLimit(data.length);
				lt_model.setData(data);
				
			ui('STUDENT_LISTING_TABLE').setModel(lt_model).bindRows("/");
			ui("STUDENT_LISTING_TABLE").setVisible(true);
			
			ui('STUDENT_LISTING_LABEL').setText("Students (" + data.length + ")");
		}
	}

	let lv_dialog_save = new sap.m.Dialog("STUD_SAVE_DIALOG",{
		title: "Confirmation",
		beginButton: new sap.m.Button({
			text:"Ok",
			type:"Accept",
			icon:"sap-icon://accept",
			press:function(oEvt){
				if(screenMode._mode == "create"){
					createSTUD();
				}else{
					bpDataOrganizer._updateById(screenMode._id);
				}

				oEvt.getSource().getParent().close();
			}
		}),
		endButton:new sap.m.Button({
			text:"Cancel",
			type:"Reject",
			icon:"sap-icon://decline",
			press:function(oEvt){
			oEvt.getSource().getParent().close();
			}
		}),
		content:[
			new sap.m.HBox({
				items:[
				new sap.m.Label({text:"Confirm to save changes?"})
				]
			})
		]
	}).addStyleClass('sapUiSizeCompact');




</script>