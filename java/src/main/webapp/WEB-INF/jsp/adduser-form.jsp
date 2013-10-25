<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="sp" uri="http://www.springframework.org/tags" %>
<%@ taglib prefix="spf" uri="http://www.springframework.org/tags/form" %>
<html>
<head>
<title>Add User</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Add User</h2>
		</div>
		<div class="span-24">
			<div>
				<spf:form commandName="addUserForm" method="POST" cssClass="inline.form">
					<fieldset>
					<legend>Add User</legend>
					<p>
					<label for="firstName">First Name</label><br/>
					<spf:input path="firstName" cssClass="text"/>
					</p>
					
					<p>
					<label for="lastName">Last Name</label><br/>
					<spf:input path="lastName" cssClass="text"/>
					</p>
					
					<p>
					<label for="email">Email</label><br/>
					<spf:input path="email" cssClass="email"/>
					</p>					
					
					<p>
					<label for="password">Password</label><br/>
					<spf:password path="password" cssClass="password" />
					</p>
					<p>
					<input type="submit" value="Submit"/>
					</p>
					</fieldset>
				</spf:form>
			</div>
		</div>
		<div class="span-24">
			<p>
				<a href="javascript:void(0);" onclick="window.history.back();">Back</a><br/><a href="<c:url value='index.html'/>">Home</a>
			</p>
		</div>		
	</div>
</body>
</html>