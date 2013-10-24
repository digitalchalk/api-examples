<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<c:if test="${!empty user}">
<p>
	<c:forEach items="${user }" var="field">
		<c:choose>
		<c:when test="${field.key eq 'id' }">
		<br/>${field.key } =>  <a href="<c:url value='getuserbyid.html?id='/>${field.value}">${field.value }</a>
		</c:when>
		<c:otherwise>
		<br/>${field.key } =>  ${field.value }
		</c:otherwise>
		</c:choose>
	</c:forEach>
</p>
</c:if>