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

package com.digitalchalk.apiexamples.model;

public class ApiConfig {

	private String apiHostname;
	private String apiToken;
	private int apiPort = 443;  // override if necessary in apiconfig.properties
	
	public String getApiHostname() {
		return apiHostname;
	}
	public void setApiHostname(String apiHostname) {
		this.apiHostname = apiHostname;
	}
	public String getApiToken() {
		return apiToken;
	}
	public void setApiToken(String apiToken) {
		this.apiToken = apiToken;
	}
	public int getApiPort() {
		return apiPort;
	}
	public void setApiPort(int apiPort) {
		this.apiPort = apiPort;
	}
	
}
