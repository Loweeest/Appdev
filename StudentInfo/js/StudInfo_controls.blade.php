<script>
    
    function CreateContent(){

        var go_Shell = new sap.m.Shell({});
        //left page
        go_App_Left = new sap.m.App({});
        go_App_Left.addPage(create_page_menu());

        //right page
        go_App_Right = new sap.m.App({});
        go_App_Right.addPage(createStudPage());	
		go_App_Right.addPage(createDisplayStudPage());
		go_App_Right.addPage(createListStud());
		//go_App_Right.addPage(createTestPage());

        go_SplitContainer = new sap.ui.unified.SplitContainer({ content: [go_App_Right], secondaryContent: [go_App_Left]});		
        go_SplitContainer.setSecondaryContentWidth("250px");
        go_SplitContainer.setShowSecondaryContent(true);
        

        let go_App = new sap.m.App({
            pages : [go_SplitContainer]
        });

        go_Shell.setApp(go_App);
        go_Shell.setAppWidthLimited(false);
        go_Shell.placeAt("content");     
    }

    function create_page_menu(){
        let page = new sap.m.Page({}).addStyleClass('sapUiSizeCompact');
        let pageHeader  = new sap.m.Bar({enableFlexBox: false,contentMiddle:[ new sap.m.Label({text:"Action"})]});
        const menuList = new sap.m.List("MENU_LIST",{});
		const menuListTemplate = new sap.m.StandardListItem("LEFT_MENU_TEMPLATE",{
			title:"{title}",
			icon:"{icon}",
			visible:"{visible}",
			type: sap.m.ListType.Active,
			press:function(oEvent){
				
                let menu = oEvent.getSource().getBindingContext().getProperty('funct');
				let list_items = oEvent.getSource().getParent().getItems();

                for (var i = 0; i < list_items.length; i++) {
                    list_items[i].removeStyleClass('class_selected_list_item');
                   //$('LEFT_MENU_TEMPLATE-MENU_LIST-0').removeClass('class_selected_list_item');
                }

                oEvent.getSource().addStyleClass('class_selected_list_item');
				
				switch(menu){
					case "CREATE_STUDENT" :
						go_App_Right.to('CREATE_STUDENT_PAGE')
						screenMode._create();
					break;
					case "DISPLAY_STUDENT" :
						go_App_Right.to('STUDENT_PAGE_DISPLAY');
					break;
					case "STUDENT_LIST" :

						listingStudent._getData(StudentData);
						go_App_Right.to('PAGE_STUDENT_LISTING');
					break;
					
				/*
					case "BP_TEST" :

						go_App_Right.to('TEST_PAGE');
					break;
				*/

				}
                
			}
		});
		
        const gt_list = [
                {
                    title   : "Add New Student",
					funct  	: "CREATE_STUDENT",
                    icon    : "sap-icon://add-employee",
                    visible : true
                },
                {
                    title   : "Search Students",
                    icon    : "sap-icon://employee-lookup",
					funct  	: "DISPLAY_STUDENT",
                    visible : true
                },
                {
                    title   : "Students Listing",
                    icon    : "sap-icon://list",
					funct  	: "STUDENT_LIST",
                    visible : true
                },
				
			/*	
				{
                    title   : "Test",
                    icon    : "sap-icon://checklist-item",
					funct  	: "BP_TEST",
                    visible : true
                }
			*/

        ];

        let model = new sap.ui.model.json.JSONModel();
			model.setSizeLimit(gt_list.length);
			model.setData(gt_list);

			ui('MENU_LIST').setModel(model).bindAggregation("items",{
				path:"/",
				template:ui('LEFT_MENU_TEMPLATE')
			});
		
        page.setCustomHeader(pageHeader);
		page.addContent(menuList);		
		return page;
    }

    function createStudPage(){
        let page  = new sap.m.Page("CREATE_STUDENT_PAGE",{}).addStyleClass('sapUiSizeCompact');
        let pageHeader = new sap.m.Bar("",{  
			enableFlexBox: false,
			contentLeft:[
				new sap.m.Button({ icon:"sap-icon://nav-back",
					press:function(oEvt){
						go_App_Right.back();
					} 
				}),
				new sap.m.Button({icon:"sap-icon://menu2",
					press:function(){
						go_SplitContainer.setSecondaryContentWidth("250px");
						if(!go_SplitContainer.getShowSecondaryContent()){
							go_SplitContainer.setShowSecondaryContent(true);
						} else {							
							go_SplitContainer.setShowSecondaryContent(false);
						}
					
					}
				}), 
				
			],
			contentMiddle:[
                new sap.m.Label("STUD_TITLE",{text:"Add New Student"})
            ],
		
		});
        let crumbs = new sap.m.Breadcrumbs("CREATE_STUDENT_BRDCRMS",{
            currentLocationText: "Add New Student",
            links: [
                new sap.m.Link({
                    text:"Home",
                    press:function(oEvt){
                       // fn_click_breadcrumbs("HOME");
                    }
                }),
				new sap.m.Link("CREATE_STUDENT_BRDCRMS_TITLE",{
                    text:"Student Information Management",
                    press:function(oEvt){
                      //  fn_click_breadcrumbs("HOME");
                    }
                }),
				
            ]
        });
		let errorPanel = new sap.m.Panel("MESSAGE_STRIP_STUD_ERROR",{visible:false});
        let createPageFormHeader = new sap.uxap.ObjectPageLayout({
            headerTitle:[
                new sap.uxap.ObjectPageHeader("OBJECTHEADER_STUD_NAME",{
                    objectTitle:"",
					showPlaceholder : false,
					actions:[
                        new sap.uxap.ObjectPageHeaderActionButton("CREATE_STUDENT_SAVE_BTN1",{
                            icon: "sap-icon://save",
							press: function(evt){
								createSTUD();

                            }
                        }).addStyleClass("sapMTB-Transparent-CTX"),
                        new sap.uxap.ObjectPageHeaderActionButton("CREATE_STUDENT_EDIT_BTN1",{
                            icon: "sap-icon://edit",
							press: function(){
									ui("COMPCODE_SAVE_DIALOG").open();
                            }
                        }).addStyleClass("sapMTB-Transparent-CTX"),

                    ],
                })
            ]     
        });

		let createPageFormContent = new sap.m.Panel("STUD_GENERAL_PANEL",{
			headerToolbar: [
				new sap.m.Toolbar({
                    content: [
                        new sap.m.ToolbarSpacer(),
                        new sap.m.Button("CREATE_STUDENT_SAVE_BTN", {
                            visible: true,
                            icon: "sap-icon://save",
                            press: function () {
								ui('STUDENT_ID').setValueState("None").setValueStateText("");
								ui('MESSAGE_STRIP_STUD_ERROR').destroyContent().setVisible(false);
								let stId = ui('STUDENT_ID').getValue().trim();
								let message = "";
								let lv_message_strip = "";
									if(stId){
										if(screenMode._mode == "create"){
											let isExist = StudentDataOrganizer._validateSTUD(stId);
											if(isExist){
												message = "Student ID already exist";
												ui('STUDENT_ID').setValueState("Error").setValueStateText(message);
												lv_message_strip = fn_show_message_strip("MESSAGE_STRIP_STUD_ERROR",message);
												ui('MESSAGE_STRIP_STUD_ERROR').setVisible(true).addContent(lv_message_strip);
											}else{
												ui('STUD_SAVE_DIALOG').open();
											}
										}else{
											ui('STUD_SAVE_DIALOG').open();
										}
										
									}else{
										message = "Student ID is mandatory";
										ui('STUDENT_ID').setValueState("Error").setValueStateText(message);
										lv_message_strip = fn_show_message_strip("MESSAGE_STRIP_BP_ERROR",message);
										ui('MESSAGE_STRIP_BP_ERROR').setVisible(true).addContent(lv_message_strip);
									}
												
                            }
                        }),
						new sap.m.Button("CREATE_STUDENT_EDIT_BTN", {
                            visible: true,
                            icon: "sap-icon://edit",
                            press: function () {
								screenMode._edit();
                            }
                        }),
						new sap.m.Button("CREATE_STUDENT_CANCEL_BTN", {
                            visible: true,
                            icon: "sap-icon://decline",
                            press: function () {
								screenMode._display(screenMode._id);
                            }
                        }),
                    ]
                }).addStyleClass('class_transparent_bar'),

			],
			content: [
                new sap.ui.layout.Grid({
                    defaultSpan:"L12 M12 S12",
					width:"auto",
					content:[
                        new sap.ui.layout.form.SimpleForm("PANEL_FORM",{
							title: "New Student",
                            maxContainerCols:2,
							labelMinWidth:130,
							content:[
								new sap.ui.core.Title("GENERAL_INFO_TITLE1",{text:""}),
                                new sap.m.Label({text:"Student ID Number",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("STUDENT_ID",{value:"", 
									liveChange: function(oEvt){
									fn_livechange_numeric_input(oEvt);
									}, width:TextWidth}),

                                new sap.m.Label({text:"First Name",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("FIRST_NAME",{value:"", width:TextWidth}),
                                                        
                                new sap.m.Label({text:"Last Name",width:labelWidth}).addStyleClass('class_label_padding'),
								new sap.m.Input("LAST_NAME",{
									value:"", 
									width:TextWidth,
									liveChange : function(oEvt){
									var lv_value = oEvt.getSource().getValue().trim();
																/* 
																if(gv_partner_ind){
																	var lv_obj_header = ""
																	var label = "New Business Partner"
																	lv_obj_header = label + " (" + lv_value + ")";
																	ui("OBJECTHEADER_BP_NAME").setObjectTitle(lv_obj_header).setObjectSubtitle("");
																}
																*/
										}
									}),
														
									new sap.m.Label({text:"Date of Birth",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("DATE_OF_BIRTH",{width:TextWidth}),

									new sap.m.Label({text:"Gender",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("GENDER",{value:"", width:TextWidth}),
														
									new sap.m.Label({text:"Contact Information",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("CONTACT_INFO",{width:TextWidth}),

									new sap.m.Label({text:"Emergency Contact Information",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("EM_CONT",{width:TextWidth}),

									new sap.m.Label({text:"Nationality",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("NATIONALITY",{width:TextWidth}),

                                    new sap.ui.core.Title("STUDENT_INFO",{text:""}),

									new sap.m.Label({text:"Ethnicity",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("ETHNICITY",{width:TextWidth}),

									new sap.m.Label({text:"Enrollment Status",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("ENROLL_STAT",{width:TextWidth}),

									new sap.m.Label({text:"Attendance Record",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("ATT_REC",{width:TextWidth}),

									new sap.m.Label({text:"Academic Record",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("ACAD_REC",{width:TextWidth}),

									new sap.m.Label({text:"Program of Study",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("PROG_STUD",{width:TextWidth}),

									new sap.m.Label({text:"Financial Aid Information",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("FINAN_AID",{width:TextWidth}),

									new sap.m.Label({text:"Graduate Date",width:labelWidth}).addStyleClass('class_label_padding'),
									new sap.m.Input("GRAD_DATE",{width:TextWidth}),

                            ]
                        })
                    ]
                })
            ]
        });

        page.setCustomHeader(pageHeader);
        page.addContent(crumbs);
		page.addContent(errorPanel);
        //page.addContent(createPageFormHeader);
		page.addContent(createPageFormContent);
        return page;
    }

	function createDisplayStudPage(){
				
		var lv_Page  = new sap.m.Page("STUDENT_PAGE_DISPLAY",{}).addStyleClass('sapUiSizeCompact');
		
		var lv_header = new sap.m.Bar({
			enableFlexBox: false,
			contentLeft:[
				new sap.m.Button({ icon:"sap-icon://nav-back",
					press:function(oEvt){
						go_App_Right.back();
					} 
				}),
				new sap.m.Button({icon:"sap-icon://menu2",
					press:function(){
						go_SplitContainer.setSecondaryContentWidth("250px");
						if(!go_SplitContainer.getShowSecondaryContent()){
							go_SplitContainer.setShowSecondaryContent(true);
						} else {							
							go_SplitContainer.setShowSecondaryContent(false);
						}
					}
				})
				//new sap.m.Image({src: logo_path}),
			],

			contentMiddle:[gv_Lbl_NewPrdPage_Title = new sap.m.Label("DISP_STUD_TITLE",{text:"Search Students"})],
			
			contentRight:[
				new sap.m.Button({
					icon: "sap-icon://home",
					press: function(){
						window.location.href = MainPageLink; 
					}
				})
			]
		});
		
		var lv_crumbs = new sap.m.Breadcrumbs("DISP_STUDENT_BRDCRMS",{
            currentLocationText: "Search Student",
            links: [
                new sap.m.Link({
                    text:"Home",
                    press:function(oEvt){
                       // fn_click_breadcrumbs("HOME");
                    }
                }),
				new sap.m.Link("DISP_STUDENT_BRDCRMS_TITLE",{
                    text:"Student Information Management",
                    press:function(oEvt){
                      //  fn_click_breadcrumbs("HOME");
                    }
                }),
				
            ]
        }).addStyleClass('breadcrumbs-padding');
		
		
		var lv_searchfield =  new sap.m.Bar({
			enableFlexBox: false,
			contentLeft: [
				// actual search field
				new sap.m.SearchField("SEARCHFIELD_DISPLAY_OUTLET",{
					width: "99%",
					liveChange: function(oEvt){
						var lv_search_val = oEvt.getSource().getValue().trim();
						if(lv_search_val == ""){
							ui("DISPLAY_STUDENT_TABLE").setVisible(false);
						}
					},
					placeholder: "Search...",
					search: function(oEvent){
						var lv_searchval = oEvent.getSource().getValue().trim();
						displayStudent._get_data(lv_searchval);
					},
				})
			],
		});
		
		var lv_table = new sap.ui.table.Table("DISPLAY_STUDENT_TABLE", {
			visible:false,
			visibleRowCountMode:"Auto",
			selectionMode:"None",
			enableCellFilter: true,
			enableColumnReordering:true,
			toolbar:[
				new sap.m.Toolbar({
					design:"Transparent",
					content:[
						new sap.m.Text("DISPLAY_STUDENT_TABLE_LABEL",{text:"List (0)"}),
					]
				})
			],
			filter:function(oEvt){
				oEvt.getSource().getBinding("rows").attachChange(function(oEvt){
					var lv_row_count = oEvt.getSource().iLength;
					ui('DISPLAY_STUDENT_TABLE_LABEL').setText("List (" + lv_row_count + ")");
				});
			},
			cellClick: function(oEvt){
				
				var lv_bind = oEvt.getParameters().rowBindingContext;
				
				if(lv_bind != undefined){
					var lv_st_id = oEvt.getParameters().rowBindingContext.getProperty("STUDENT_ID");
					if(lv_st_id){
						screenMode._display(lv_st_id);
					}
				}
				
			},
			columns: [
			
				new sap.ui.table.Column({label:new sap.m.Text({text:"Student ID"}),
					width:"180px",
					sortProperty:"STUDENT_ID",
					filterProperty:"STUDENT_ID",
					//autoResizable:true,
					template:new sap.m.Text({text:"{STUDENT_ID}",tooltip:"{STUDENT_ID}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"First Name"}),
					width:"250px",
					sortProperty:"FIRST_NAME",
					filterProperty:"FIRST_NAME",
					autoResizable:true,
					template:new sap.m.Text({text:"{FIRST_NAME}",tooltip:"{FIRST_NAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Last Name"}),
					width:"250px",
					sortProperty:"LAST_NAME",
					filterProperty:"LAST_NAME",
					template:new sap.m.Text({text:"{LAST_NAME}",tooltip:"{LAST_NAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Date of Birth"}),
					width:"180px",
					sortProperty:"DATE_OF_BIRTH",
					filterProperty:"DATE_OF_BIRTH",
					template:new sap.m.Text({text:"{DATE_OF_BIRTH}",tooltip:"{DATE_OF_BIRTH}",maxLines:1}),
				}),
				
				
				new sap.ui.table.Column({label:new sap.m.Text({text:"Gender"}),
					width:"180px",
					sortProperty:"GENDER",
					filterProperty:"GENDER",
					template:new sap.m.Text({text:"{GENDER}",tooltip:"{GENDER}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Contact Information"}),
					width:"180px",
					sortProperty:"CONTACT_INFO",
					filterProperty:"CONTACT_INFO",
					template:new sap.m.Text({text:"{CONTACT_INFO}",tooltip:"{CONTACT_INFO}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Emergency Contact Information"}),
					width:"180px",
					sortProperty:"EM_CONT",
					filterProperty:"EM_CONT",
					template:new sap.m.Text({text:"{EM_CONT}",tooltip:"{EM_CONT}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Nationality"}),
					width:"180px",
					sortProperty:"NATIONALITY",
					filterProperty:"NATIONALITY",
					template:new sap.m.Text({text:"{NATIONALITY}",tooltip:"{NATIONALITY}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Ethnicity"}),
					width:"180px",
					sortProperty:"ETHNICITY",
					filterProperty:"ETHNICITY",
					template:new sap.m.Text({text:"{ETHNICITY}",tooltip:"{ETHNICITY}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Enrollment Status"}),
					width:"180px",
					sortProperty:"ENROLL_STAT",
					filterProperty:"ENROLL_STAT",
					template:new sap.m.Text({text:"{ENROLL_STAT}",tooltip:"{ENROLL_STAT}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Attendance Record"}),
					width:"180px",
					sortProperty:"ATT_REC",
					filterProperty:"ATT_REC",
					template:new sap.m.Text({text:"{ATT_REC}",tooltip:"{ATT_REC}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Academic Record"}),
					width:"180px",
					sortProperty:"ACAD_REC",
					filterProperty:"ACAD_REC",
					template:new sap.m.Text({text:"{ACAD_REC}",tooltip:"{ACAD_REC}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Program of Study"}),
					width:"180px",
					sortProperty:"PROG_STUD",
					filterProperty:"PROG_STUD",
					template:new sap.m.Text({text:"{PROG_STUD}",tooltip:"{PROG_STUD}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Financial Aid Information"}),
					width:"180px",
					sortProperty:"FINAN_AID",
					filterProperty:"FINAN_AID",
					template:new sap.m.Text({text:"{FINAN_AID}",tooltip:"{FINAN_AID}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Graduate Date"}),
					width:"180px",
					sortProperty:"GRAD_DATE",
					filterProperty:"GRAD_DATE",
					template:new sap.m.Text({text:"{GRAD_DATE}",tooltip:"{GRAD_DATE}",maxLines:1}),
				})
				
			]
		});
		
		lv_Page.setCustomHeader(lv_header);
		lv_Page.addContent(lv_crumbs);
		lv_Page.addContent(lv_searchfield);
		lv_Page.addContent(lv_table);
		
		return lv_Page;
	}

	function createListStud(){

		var lv_Page  = new sap.m.Page("PAGE_STUDENT_LISTING",{}).addStyleClass('sapUiSizeCompact');

		var lv_header = new sap.m.Bar({
			enableFlexBox: false,
			contentLeft:[
				new sap.m.Button({ icon:"sap-icon://nav-back",
					press:function(oEvt){ 
						
						go_App_Right.back();
						
					}
				}),
				new sap.m.Button({icon:"sap-icon://menu2",
					press:function(){
						go_SplitContainer.setSecondaryContentWidth("270px");
						if(!go_SplitContainer.getShowSecondaryContent()){
							go_SplitContainer.setShowSecondaryContent(true);
						} else {							
							go_SplitContainer.setShowSecondaryContent(false);
						}
					}
				}), 
				//new sap.m.Image({src: logo_path}),
				],
			contentMiddle:[gv_Lbl_NewPrdPage_Title = new sap.m.Label("STUDENT_LISTING_PAGE_LABEL",{text:"Students Listing"})],
			
			contentRight:[
				//fn_help_button(SelectedAppID,"STUDENT_LISTING"),
				new sap.m.Button({  
					icon: "sap-icon://home",
					press: function(){
					window.location.href = MainPageLink; 
					}
				})
			]
		});
					
		var lv_crumbs = new sap.m.Breadcrumbs("LIST_STUDENT_BRDCRMS",{
			currentLocationText: "Students Listing",
			links: [
				new sap.m.Link({
					text:"Home",
					press:function(oEvt){
					// fn_click_breadcrumbs("HOME");
					}
				}),
				new sap.m.Link("LIST_STUDENT_BRDCRMS_TITLE",{
					text:"Student Information Management",
					press:function(oEvt){
					//  fn_click_breadcrumbs("HOME");
					}
				}),
				
			]
		}).addStyleClass('breadcrumbs-padding');


		var lv_table = new sap.ui.table.Table("STUDENT_LISTING_TABLE",{
				visibleRowCountMode:"Auto",
				selectionMode:"None",
				enableCellFilter: true,
				enableColumnReordering:false,
				columnResize: false,
				filter:function(oEvt){
				oEvt.getSource().getBinding("rows").attachChange(function(oEvt){
					var lv_row_count = oEvt.getSource().iLength;
					ui('STUDENT_LISTING_LABEL').setText("Students (" + lv_row_count + ")");
				});
			},
			toolbar: [
                new sap.m.Toolbar({
                    content: [
                        new sap.m.Label("STUDENT_LISTING_LABEL", {
                            text:"Students (0)"
                        }),
                        new sap.m.ToolbarSpacer(),
                        new sap.m.Button("BTN_DOWNLOAD", {
                            visible: true,
                            icon: "sap-icon://download",
                            press: function () {
								
                            }
                        })
                    ]
                }).addStyleClass('class_transparent_bar'),
            ],
			cellClick: function(oEvt){
				
				var lv_bind = oEvt.getParameters().rowBindingContext;
				
				if(lv_bind != undefined){
					var lv_st_id = oEvt.getParameters().rowBindingContext.getProperty("STUDENT_ID");
					if(lv_st_id){
						screenMode._display(lv_st_id);
					}
				}
			},
			columns:[
				
				new sap.ui.table.Column({label:new sap.m.Text({text:"Student ID"}),
					width:"180px",
					sortProperty:"STUDENT_ID",
					filterProperty:"STUDENT_ID",
					//autoResizable:true,
					template:new sap.m.Text({text:"{STUDENT_ID}",tooltip:"{STUDENT_ID}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"First Name"}),
					width:"250px",
					sortProperty:"FIRST_NAME",
					filterProperty:"FIRST_NAME",
					autoResizable:true,
					template:new sap.m.Text({text:"{FIRST_NAME}",tooltip:"{FIRST_NAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Last Name"}),
					width:"250px",
					sortProperty:"LAST_NAME",
					filterProperty:"LAST_NAME",
					template:new sap.m.Text({text:"{LAST_NAME}",tooltip:"{LAST_NAME}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Date of Birth"}),
					width:"180px",
					sortProperty:"DATE_OF_BIRTH",
					filterProperty:"DATE_OF_BIRTH",
					template:new sap.m.Text({text:"{DATE_OF_BIRTH}",tooltip:"{DATE_OF_BIRTH}",maxLines:1}),
				}),
				
				
				new sap.ui.table.Column({label:new sap.m.Text({text:"Gender"}),
					width:"180px",
					sortProperty:"GENDER",
					filterProperty:"GENDER",
					template:new sap.m.Text({text:"{GENDER}",tooltip:"{GENDER}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Contact Information"}),
					width:"180px",
					sortProperty:"CONTACT_INFO",
					filterProperty:"CONTACT_INFO",
					template:new sap.m.Text({text:"{CONTACT_INFO}",tooltip:"{CONTACT_INFO}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Emergency Contact Information"}),
					width:"180px",
					sortProperty:"EM_CONT",
					filterProperty:"EM_CONT",
					template:new sap.m.Text({text:"{EM_CONT}",tooltip:"{EM_CONT}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Nationality"}),
					width:"180px",
					sortProperty:"NATIONALITY",
					filterProperty:"NATIONALITY",
					template:new sap.m.Text({text:"{NATIONALITY}",tooltip:"{NATIONALITY}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Ethnicity"}),
					width:"180px",
					sortProperty:"ETHNICITY",
					filterProperty:"ETHNICITY",
					template:new sap.m.Text({text:"{ETHNICITY}",tooltip:"{ETHNICITY}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Enrollment Status"}),
					width:"180px",
					sortProperty:"ENROLL_STAT",
					filterProperty:"ENROLL_STAT",
					template:new sap.m.Text({text:"{ENROLL_STAT}",tooltip:"{ENROLL_STAT}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Attendance Record"}),
					width:"180px",
					sortProperty:"ATT_REC",
					filterProperty:"ATT_REC",
					template:new sap.m.Text({text:"{ATT_REC}",tooltip:"{ATT_REC}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Academic Record"}),
					width:"180px",
					sortProperty:"ACAD_REC",
					filterProperty:"ACAD_REC",
					template:new sap.m.Text({text:"{ACAD_REC}",tooltip:"{ACAD_REC}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Program of Study"}),
					width:"180px",
					sortProperty:"PROG_STUD",
					filterProperty:"PROG_STUD",
					template:new sap.m.Text({text:"{PROG_STUD}",tooltip:"{PROG_STUD}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Financial Aid Information"}),
					width:"180px",
					sortProperty:"FINAN_AID",
					filterProperty:"FINAN_AID",
					template:new sap.m.Text({text:"{FINAN_AID}",tooltip:"{FINAN_AID}",maxLines:1}),
				}),
				new sap.ui.table.Column({label:new sap.m.Text({text:"Graduate Date"}),
					width:"180px",
					sortProperty:"GRAD_DATE",
					filterProperty:"GRAD_DATE",
					template:new sap.m.Text({text:"{GRAD_DATE}",tooltip:"{GRAD_DATE}",maxLines:1}),
				})
				
			]

		});

		lv_Page.setCustomHeader(lv_header);
		lv_Page.addContent(lv_crumbs);
		lv_Page.addContent(lv_table);


		return lv_Page;
	}

	/*
	function createTestPage(){
		let page = new sap.m.Page("TEST_PAGE",{}).addStyleClass('sapUiSizeCompact');
		let crumbs = new sap.m.Breadcrumbs("TEST_CRUMBS",{
			currentLocationText: "Business Partner Listing",
			links: [
				new sap.m.Link({
					text:"Home",
					press:function(oEvt){
					// fn_click_breadcrumbs("HOME");
					}
				}),
				new sap.m.Link("TEST_LIST_CRUMBS",{
					text:"Business Partner Management",
					press:function(oEvt){
					//  fn_click_breadcrumbs("HOME");
					}
				}),
				
			]
		}).addStyleClass('breadcrumbs-padding');

		//page.addContent(crumbs);
		return page;


	} */



</script>
