<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<html>
<body>
	<ul>
	<li><a href="<c:url value='/getallusers.html'/>">Get All Users</a></li>
	<li><a href="<c:url value='/getuserbyemail.html'/>">Get User(s) by email</a></li>
	<li><a href="<c:url value='/adduser.html'/>">Add User</a></li>
	</ul>
</body>
</html>
