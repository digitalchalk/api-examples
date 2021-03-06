<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<html>
<head>
<title>API v5 Java Examples Home</title>
<jsp:include page="/WEB-INF/jsp/common/inccss.jsp"/>
</head>
<body>
	<div class="container">
		<div class="span-24">
			<h2>API v5 Java Examples Home</h2>
		</div>
		<div class="span-24">
			<ul>
			<li><a href="<c:url value='/getallusers.html'/>">Get All Users</a></li>
			<li><a href="<c:url value='/getuserbyemail.html'/>">Get User(s) by email</a></li>
			<li><a href="<c:url value='/adduser.html'/>">Add User</a></li>
			</ul>
		</div>
	</div>
</body>
</html>
