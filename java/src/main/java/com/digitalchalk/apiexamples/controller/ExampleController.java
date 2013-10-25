/**
 * Copyright (C) 2012 Infinity Learning Solutions.  All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, is NOT permitted unless the following conditions are
 * met:
 * 1. The redistribution of any kind or in any form must be approved
 *    in writing from an official of Infinity Learning Solutions and a third
 *    party witness.
 * 2. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.  The
 *    redistribution must also be approved in writing from an official
 *    of Infinity Learning Solutions and a third party witness.
 * 3. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *    
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ''AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

package com.digitalchalk.apiexamples.controller;

import java.util.*;

import org.springframework.beans.factory.annotation.*;
import org.springframework.stereotype.*;
import org.springframework.ui.*;
import org.springframework.web.bind.annotation.*;

import com.digitalchalk.apiexamples.model.*;
import com.digitalchalk.apiexamples.service.*;


/**
 *
 */
@Controller
public class ExampleController {
		
	@Autowired
	private ApiService apiService;
	
	@RequestMapping(value="index.html")
	public String home() {
		return "index";
	}
	
	@RequestMapping(value="adduser.html", method=RequestMethod.GET) 
	public String addUserForm(ModelMap modelMap) {
		AddUserForm addUserForm = new AddUserForm();
		modelMap.addAttribute("addUserForm", addUserForm);
		return "adduser-form";
	}
	
	@RequestMapping(value="adduser.html", method=RequestMethod.POST)
	public String addUserSubmit(@ModelAttribute("addUserForm") AddUserForm addUserForm, ModelMap modelMap) {		
		ApiResult apiResult = apiService.apiPost("/dc/api/v5/users", addUserForm);
		modelMap.addAttribute("apiResult", apiResult);
		return "adduser-result";
	}
	
	@RequestMapping(value="edituser.html", method=RequestMethod.GET)
	public String editUserForm(@RequestParam(required=true) String id, ModelMap modelMap) {
		ApiResult apiResult = apiService.apiGet("/dc/api/v5/users/" + id, null);
		if(apiResult.getStatusCode() != 200) {
			modelMap.addAttribute("id", id);
			return "edituser-error";
		} else {
			@SuppressWarnings("unchecked")
			Map<String,Object> userToEdit = apiResult.getResults().get(0);
			EditUserForm editUserForm = new EditUserForm();
			editUserForm.setId(id);
			editUserForm.setFirstName((String)userToEdit.get("firstName"));
			editUserForm.setLastName((String)userToEdit.get("lastName"));
			editUserForm.setEmail((String)userToEdit.get("email"));
			
			modelMap.addAttribute("editUserForm", editUserForm);
			
			return "edituser-form";
		}
	}
	
	@RequestMapping(value="edituser.html", method=RequestMethod.POST)
	public String editUserSubmit(@ModelAttribute("editUserForm") EditUserForm editUserForm, ModelMap modelMap) {
		ApiResult apiResult = apiService.apiPut("/dc/api/v5/users/" + editUserForm.getId(), editUserForm);
		modelMap.addAttribute("apiResult", apiResult);
		modelMap.addAttribute("id", editUserForm.getId());
		return "edituser-result";
	}
	
	@RequestMapping(value="deleteuser.html")
	public String deleteUser(@RequestParam(required=true) String id, ModelMap modelMap) {
		ApiResult apiResult = apiService.apiDelete("/dc/api/v5/users/" + id);
		modelMap.addAttribute("apiResult", apiResult);
		return "deleteuser-results";
	}
	
	@RequestMapping(value="getallusers.html")
	public String getAllUsers(ModelMap modelMap) {
		ApiResult apiResult = apiService.apiGet("/dc/api/v5/users", null);
		modelMap.addAttribute("apiResult", apiResult);
		return "getuser-results";
	}
	
	@RequestMapping(value="getuserbyid.html")
	public String getUserById(@RequestParam(required=true) String id, ModelMap modelMap) {
		ApiResult apiResult = apiService.apiGet("/dc/api/v5/users/" + id, null);
		modelMap.addAttribute("apiResult", apiResult);
		return "getuser-results";
	}

	@RequestMapping(value="getuserbyemail.html", method=RequestMethod.GET)
	public String getUserByEmailProcess(@RequestParam(required=false) String email, @RequestParam(required=false)String offset, ModelMap modelMap) {
		Map<String,String> parameters = new HashMap<String,String>();
		if(email != null) {
			parameters.put("email", email);
		} else {
			return "getuser-byemail-form";
		}
		if(offset != null) {
			parameters.put("offset", offset);
		}
		ApiResult apiResult = apiService.apiGet("/dc/api/v5/users", parameters);
		modelMap.addAttribute("apiResult", apiResult);
		return "getuser-results";
	}

}
