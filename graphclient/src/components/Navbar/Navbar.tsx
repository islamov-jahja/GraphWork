import React from 'react';

interface AuthProps {
	isAuth: boolean
}

export const Navbar = (props: AuthProps) => {
	const token = localStorage.getItem('token')

	return (
		<nav>
			<div className="container">
				<div className="nav-wrapper">
					<a href="/" className="brand-logo">Граф сервис</a>
					<ul id="nav-mobile" className="right hide-on-med-and-down">
						{token ? (
							<ul id="nav-mobile" className="right hide-on-med-and-down">
								<li><a href="/logout">Выйти</a></li>
							</ul>
						) : (
								<>
									<li><a href="/login">Войти</a></li>
									<li><a href="/signup">Зарегистрироваться</a></li>
								</>
							)}
					</ul>
				</div>
			</div>
		</nav>
	);
}