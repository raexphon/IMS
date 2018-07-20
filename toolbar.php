<div class="toolbar">
	<ul class="breadcrumb">
		<li id="homebreadcrumb"><a href="index.php" title="" class="home" data-original-title="Home"><span data-phrase="HomePage" class="glyphicon glyphicon-home ewIcon" data-caption="Home"></span></a></li>
		<li id="homehelp" class="active"><span id="ewPageCaption"><?php chkclickmenu($_GET['r']);?></span></li>&nbsp;<a href="javascript:void(0);" id="helponline" onclick="msHelpDialogShow()" title="Help"><span class="glyphicon glyphicon-question-sign ewIconHelp"></span></a>
	</ul>
	<div class="exportOption ewListOptionSeparator" style="white-space: nowrap;" data-name="button">
		<div class="btn-group ewButtonDropdown">
			<button class="dropdown-toggle btn btn-default btn-sm" title="" data-toggle="dropdown" data-original-title="Export" aria-expanded="false">
				<span data-phrase="ButtonExport" class="icon-export ewIcon" data-caption="Export"></span><b class="caret"></b>
			</button>
			<ul class="dropdown-menu ewMenu">
				<li>
					<a class="exportLink print" href="a_stock_itemslist.php?export=print" data-caption="Printer Friendly" data-original-title="" title=""><span data-phrase="PrinterFriendly" class="icon-print ewIcon" data-caption="Printer Friendly"></span>&nbsp;&nbsp;Printer Friendly</a>
				</li>
				<li>
					<a class="exportLink excel" href="a_stock_itemslist.php?export=excel" data-caption="Excel" data-original-title="" title=""><span data-phrase="ExportToExcel" class="icon-excel ewIcon" data-caption="Export to Excel"></span>&nbsp;&nbsp;Excel</a>
				</li>				
				<li>
					<a class="exportLink email" id="emf_a_stock_items" href="javascript:void(0);" data-caption="Email" onclick="ew_EmailDialogShow({lnk:'emf_a_stock_items',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fa_stock_itemslist,sel:false});" data-original-title="" title=""><span data-phrase="ExportToEmail" class="icon-email ewIcon" data-caption="Email"></span>&nbsp;&nbsp;Email</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="searchOption listOptionSeparator" style="white-space: nowrap;" data-name="button">
		<div class="btn-group ewButtonGroup">
			<button class="btn btn-default advancedSearch" type="button" title="" data-caption="Advanced Search Panel" data-toggle="collapse" data-target="#fa_stock_itemslistsrch_SearchPanel" data-original-title="Advanced Search Panel">
				<span data-phrase="SearchBtn" class="icon-advanced-search ewIcon" data-caption="Advanced Search"></span>
			</button>			
		</div>
	</div>
	<!--<div class="filterOption fa_stock_itemslistsrch listOptionSeparator" data-name="button">
		<div class="btn-group ewButtonDropdown">
			<button class="dropdown-toggle btn btn-default btn-sm" title="" data-toggle="dropdown" data-original-title="Filters" aria-expanded="false"><span data-phrase="Filters" class="icon-filter ewIcon" data-caption="Filters"></span><b class="caret"></b>
			</button>
			<ul class="dropdown-menu ewMenu">
				<li class="disabled">
					<a class="ewSaveFilter" data-form="fa_stock_itemslistsrch" href="#">Save current filter</a>
				</li>
				<li class="disabled">
					<a class="ewDeleteFilter" data-form="fa_stock_itemslistsrch" href="#">Delete filter</a>
				</li>
			</ul>
		</div>
	</div>-->
	<!--<div class="languageOption">
		<div class="btn-group" data-toggle="buttons">
			<label class="btn btn-default ewTooltip active" data-container="body" data-placement="bottom" title="" data-original-title="English"><input type="radio" name="ewLanguage" autocomplete="off" onchange="ew_SetLanguage(this);" value="en" checked="">en</label>
			<label class="btn btn-default ewTooltip" data-container="body" data-placement="bottom" title="" data-original-title="Indonesian"><input type="radio" name="ewLanguage" autocomplete="off" onchange="ew_SetLanguage(this);" value="id">id</label>
		</div>
	</div>-->
	<div class="clearfix"></div>
	
</div>
<form name="fa_stock_itemslistsrch" id="fa_stock_itemslistsrch" class="form-inline ewForm" action="a_stock_itemslist.php">
	<div id="fa_stock_itemslistsrch_SearchPanel" class="ewSearchPanel collapse">
		<input type="hidden" name="cmd" value="search">		
		<div class="ewBasicSearch">
			<div id="xsr_1" class="ewRow">
				<div class="ewQuickSearch input-group">
					<input type="text" name="psearch" id="psearch" class="form-control" value="" placeholder="Search">
					<input type="hidden" name="psearchtype" id="psearchtype" value="">					
					<span class="input-group-btn">
						<button type="button" data-toggle="dropdown" class="btn btn-default btn-lg glyphicon glyphicon-th-list" style="line-height:1px;"><span id="searchtype"></span><span class="caret"></span></button>
						<ul class="dropdown-menu pull-right" role="menu">
							<div style="margin-left:10px">	
								<li>
									<label>Code</label>
									<input type="radio" name="searchcode" id="searchcode" checked/>
								</li>
								<hr style="margin:7px 0;">
								<li>
									<label>Description</label>
									<input type="radio" name="searchdesc" id="searchdesc" />
								</li>
								<hr style="margin:7px 0;">
								<li>
									<label>Product Line</label>
									<input type="radio" name="searchpline" id="searchpline" />
								</li>
								<hr style="margin:7px 0;">
								<li>
									<label>Inactive</label>
									<input type="checkbox" name="searchinactive" id="searchinactive" value="N" />
								</li>
							</div>
						</ul>
					</span>
					<!--<div class="input-group-btn">
						<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"></span><span class="caret"></span></button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li class="active"><a href="javascript:void(0);" onclick="ew_SetSearchType(this)">Auto</a></li>
							<li><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')">Exact match</a></li>
							<li><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')">All keywords</a></li>
							<li><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')">Any keywords</a></li>
						</ul>
						<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit">Search</button>
					</div>-->
				</div>
			</div>
		</div>
	</div>
</form>
