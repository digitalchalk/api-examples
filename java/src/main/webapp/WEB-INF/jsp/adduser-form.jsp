<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
 <%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<html>
<head>
<title>Add User</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>Get User(s) By Email</h2>
		</div>
		<div class="span-24">
			<div>
				<form method="POST" action="<c:url value='adduser.html'/>" class="inline.form">
					<fieldset>
					<legend>Add User</legend>
					<p>
					<label for="firstName">First Name</label><br/>
					<input type="text" name="firstName" class="text"/>
					</p>
					
					<p>
					<label for="lastName">Last Name</label><br/>
					<input type="text" name="lastName" class="text"/>
					</p>
					
					<p>
					<label for="email">Email</label><br/>
					<input type="text" name="email" class="email"/>
					</p>					
					
					<p>
					<label for="password">Password</label><br/>
					<input type="text" name="password" class="password"/>
					</p>
					<p>
					<input type="submit" value="Submit"/>
					</p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</body>
</html>